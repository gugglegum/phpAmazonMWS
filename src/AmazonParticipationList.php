<?php
/**
 * Copyright 2013 CPI Group, LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 *
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace gugglegum\AmazonMWS;

/**
 * Gets the Participation list from Amazon.
 *
 * This Amazon Sellers Core object retrieves the list of the sellers'
 * Marketplace Participations from Amazon. It has no parameters other
 * than potential use of tokens.
 */
class AmazonParticipationList extends AmazonSellersCore
{
    /**
     * @var bool
     */
    protected $tokenFlag = false;

    /**
     * @var bool
     */
    protected $tokenUseFlag = false;

    /**
     * Participation list
     *
     * @var array[]         Indexed array of associative arrays
     */
    protected $participationList;

    /**
     * Marketplace list
     *
     * @var array[]         Indexed array of associative arrays
     */
    protected $marketplaceList;

    /**
     * Index for marketplaces
     *
     * @var int
     */
    protected $indexM = 0;

    /**
     * Index for participations
     *
     * @var int
     */
    protected $indexP = 0;

    /**
     * Gets list of marketplaces run by the seller.
     *
     * The parameters are passed to the parent constructor, which are
     * in turn passed to the AmazonCore constructor. See it for more information
     * on these parameters and common methods.
     * @param array $config A config array to set.
     * @param boolean $mock [optional] This is a flag for enabling Mock Mode.
     * This defaults to FALSE.
     * @param array|string $m [optional] The files (or file) to use in Mock Mode.
     */
    public function __construct(array $config, $mock = false, $m = null)
    {
        parent::__construct($config, $mock, $m);
        include($this->env);

        if (isset($THROTTLE_LIMIT_SELLERS)) {
            $this->throttleLimit = $THROTTLE_LIMIT_SELLERS;
        }
        if (isset($THROTTLE_TIME_SELLERS)) {
            $this->throttleTime = $THROTTLE_TIME_SELLERS;
        }
        $this->throttleGroup = 'ParticipationList';
    }

    /**
     * Returns whether or not a token is available.
     * @return boolean
     */
    public function hasToken()
    {
        return $this->tokenFlag;
    }

    /**
     * Sets whether or not the object should automatically use tokens if it receives one.
     *
     * If this option is set to TRUE, the object will automatically perform
     * the necessary operations to retrieve the rest of the list using tokens. If
     * this option is off, the object will only ever retrieve the first section of
     * the list.
     * @param boolean $b [optional] Defaults to TRUE
     * @return boolean FALSE if improper input
     */
    public function setUseToken($b = true)
    {
        if (is_bool($b)) {
            $this->tokenUseFlag = $b;
        } else {
            return false;
        }
    }

    /**
     * Fetches the participation list from Amazon.
     *
     * Submits a `ListMarketplaceParticipations` request to Amazon. Amazon will send
     * the list back as a response, which can be retrieved using `getMarketplaceList()`
     * and  `getParticipationList()`.
     * Other methods are available for fetching specific values from the list.
     * This operation can potentially involve tokens.
     * @param boolean $r [optional] When set to FALSE, the function will not recurse, defaults to TRUE
     * @return boolean FALSE if something goes wrong
     */
    public function fetchParticipationList($r = true)
    {
        $this->prepareToken();

        $url = $this->urlbase . $this->urlbranch;

        $query = $this->genQuery();

        $path = $this->options['Action'] . 'Result';

        if ($this->mockMode) {
            $xml = $this->fetchMockFile()->$path;
        } else {
            $response = $this->sendRequest($url, array('Post' => $query));

            if (!$this->checkResponse($response)) {
                return false;
            }

            $xml = simplexml_load_string($response['body'])->$path;
        }

        $this->parseXML($xml);

        $this->checkToken($xml);

        if ($this->tokenFlag && $this->tokenUseFlag && $r === true) {
            while ($this->tokenFlag) {
                $this->log("Recursively fetching more Participationseses");
                $this->fetchParticipationList(false);
            }
        }
    }

    /**
     * Sets up options for using tokens.
     *
     * This changes key options for switching between simply fetching a list and
     * fetching the rest of a list using a token. Please note: because the
     * operation for using tokens does not use any other parameters, all other
     * parameters will be removed.
     */
    protected function prepareToken()
    {
        if ($this->tokenFlag && $this->tokenUseFlag) {
            $this->options['Action'] = 'ListMarketplaceParticipationsByNextToken';
        } else {
            $this->options['Action'] = 'ListMarketplaceParticipations';
            unset($this->options['NextToken']);
            $this->marketplaceList = array();
            $this->participationList = array();
            $this->indexM = 0;
            $this->indexP = 0;
        }
    }

    /**
     * Parses XML response into two arrays.
     *
     * This is what reads the response XML and converts it into two arrays.
     * @param \SimpleXMLElement $xml The XML response from Amazon.
     * @return boolean FALSE if no XML data is found
     */
    protected function parseXML($xml)
    {
        if (!$xml) {
            return false;
        }
        $xmlP = $xml->ListParticipations;
        $xmlM = $xml->ListMarketplaces;

        foreach ($xmlP->children() as $x) {
            $this->participationList[$this->indexP]['MarketplaceId'] = (string)$x->MarketplaceId;
            $this->participationList[$this->indexP]['SellerId'] = (string)$x->SellerId;
            $this->participationList[$this->indexP]['Suspended'] = (string)$x->HasSellerSuspendedListings;
            $this->indexP++;
        }

        foreach ($xmlM->children() as $x) {
            $this->marketplaceList[$this->indexM]['MarketplaceId'] = (string)$x->MarketplaceId;
            $this->marketplaceList[$this->indexM]['Name'] = (string)$x->Name;
            $this->marketplaceList[$this->indexM]['Country'] = (string)$x->DefaultCountryCode;
            $this->marketplaceList[$this->indexM]['Currency'] = (string)$x->DefaultCurrencyCode;
            $this->marketplaceList[$this->indexM]['Language'] = (string)$x->DefaultLanguageCode;
            $this->marketplaceList[$this->indexM]['Domain'] = (string)$x->DomainName;
            $this->indexM++;
        }
    }

    /**
     * Returns the list of marketplaces.
     *
     * The returned array will contain a list of arrays, each with the following fields:
     *
     *  - MarketplaceId
     *  - Name
     *  - Country
     *  - Currency
     *  - Language
     *  - Domain
     *
     * @return array|boolean multi-dimensional array, or FALSE if list not filled yet
     */
    public function getMarketplaceList()
    {
        if (isset($this->marketplaceList)) {
            return $this->marketplaceList;
        } else {
            return false;
        }
    }

    /**
     * Returns the list of participations.
     *
     * The returned array will contain a list of arrays, each with the following fields:
     *
     *  - MarketplaceId
     *  - SellerId
     *  - Suspended
     *
     * @return array|boolean multi-dimensional array, or FALSE if list not filled yet
     */
    public function getParticipationList()
    {
        if (isset($this->participationList)) {
            return $this->participationList;
        } else {
            return false;
        }
    }

    /**
     * Returns the marketplace ID for the specified entry.
     *
     * This method will return FALSE if the list has not yet been filled.
     * @param int $i [optional] List index to retrieve the value from. Defaults to 0.
     * @return string|boolean single value, or FALSE if Non-numeric index
     */
    public function getMarketplaceId($i = 0)
    {
        if (!isset($this->marketplaceList)) {
            return false;
        }
        if (is_numeric($i) && array_key_exists($i, $this->marketplaceList)) {
            return $this->marketplaceList[$i]['MarketplaceId'];
        } else {
            return false;
        }
    }

    /**
     * Returns the marketplace name for the specified entry.
     *
     * This method will return FALSE if the list has not yet been filled.
     * @param int $i [optional] List index to retrieve the value from. Defaults to 0.
     * @return string|boolean single value, or FALSE if Non-numeric index
     */
    public function getName($i = 0)
    {
        if (!isset($this->marketplaceList)) {
            return false;
        }
        if (is_numeric($i) && array_key_exists($i, $this->marketplaceList)) {
            return $this->marketplaceList[$i]['Name'];
        } else {
            return false;
        }
    }

    /**
     * Returns the country code for the specified entry.
     *
     * This method will return FALSE if the list has not yet been filled.
     * @param int $i [optional] List index to retrieve the value from. Defaults to 0.
     * @return string|boolean single value, or FALSE if Non-numeric index
     */
    public function getCountry($i = 0)
    {
        if (!isset($this->marketplaceList)) {
            return false;
        }
        if (is_numeric($i) && array_key_exists($i, $this->marketplaceList)) {
            return $this->marketplaceList[$i]['Country'];
        } else {
            return false;
        }
    }

    /**
     * Returns the default currency code for the specified entry.
     *
     * This method will return FALSE if the list has not yet been filled.
     * @param int $i [optional] List index to retrieve the value from. Defaults to 0.
     * @return string|boolean single value, or FALSE if Non-numeric index
     */
    public function getCurreny($i = 0)
    {
        if (!isset($this->marketplaceList)) {
            return false;
        }
        if (is_numeric($i) && array_key_exists($i, $this->marketplaceList)) {
            return $this->marketplaceList[$i]['Currency'];
        } else {
            return false;
        }
    }

    /**
     * Returns the default language code for the specified entry.
     *
     * This method will return FALSE if the list has not yet been filled.
     * @param int $i [optional] List index to retrieve the value from. Defaults to 0.
     * @return string|boolean single value, or FALSE if Non-numeric index
     */
    public function getLanguage($i = 0)
    {
        if (!isset($this->marketplaceList)) {
            return false;
        }
        if (is_numeric($i) && array_key_exists($i, $this->marketplaceList)) {
            return $this->marketplaceList[$i]['Language'];
        } else {
            return false;
        }
    }

    /**
     * Returns the domain name for the specified entry.
     *
     * This method will return FALSE if the list has not yet been filled.
     * @param int $i [optional] List index to retrieve the value from. Defaults to 0.
     * @return string|boolean single value, or FALSE if Non-numeric index
     */
    public function getDomain($i = 0)
    {
        if (!isset($this->marketplaceList)) {
            return false;
        }
        if (is_numeric($i) && array_key_exists($i, $this->marketplaceList)) {
            return $this->marketplaceList[$i]['Domain'];
        } else {
            return false;
        }
    }

    /**
     * Returns the seller ID for the specified entry.
     *
     * This method will return FALSE if the list has not yet been filled.
     * @param int $i [optional] List index to retrieve the value from. Defaults to 0.
     * @return string|boolean single value, or FALSE if Non-numeric index
     */
    public function getSellerId($i = 0)
    {
        if (!isset($this->participationList)) {
            return false;
        }
        if (is_numeric($i) && array_key_exists($i, $this->participationList)) {
            return $this->participationList[$i]['SellerId'];
        } else {
            return false;
        }
    }

    /**
     * Returns the suspension status for the specified entry.
     *
     * This method will return FALSE if the list has not yet been filled.
     * @param int $i [optional] List index to retrieve the value from. Defaults to 0.
     * @return string|boolean "Yes" or "No", or FALSE if Non-numeric index
     */
    public function getSuspensionStatus($i = 0)
    {
        if (!isset($this->participationList)) {
            return false;
        }
        if (is_numeric($i) && array_key_exists($i, $this->participationList)) {
            return $this->participationList[$i]['Suspended'];
        } else {
            return false;
        }
    }
}
