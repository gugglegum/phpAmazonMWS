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

namespace gugglegum\phpAmazonMWS;

/**
 * Core class for Amazon Reports API.
 * 
 * This is the core class for all objects in the Amazon Reports section.
 * It contains no methods in itself other than the constructor.
 */
abstract class AmazonReportsCore extends AmazonCore{
    /**
     * AmazonReportsCore constructor sets up key information used in all Amazon Reports Core requests
     * 
     * This constructor is called when initializing all objects in the Amazon Reports Core.
     * The parameters are passed by the child objects' constructors, which are
     * in turn passed to the AmazonCore constructor. See it for more information
     * on these parameters and common methods.
     * @param array $config <p>A config array to set.</p>
     * @param boolean $mock [optional] <p>This is a flag for enabling Mock Mode.
     * This defaults to <b>FALSE</b>.</p>
     * @param array|string $m [optional] <p>The files (or file) to use in Mock Mode.</p>
     */
    public function __construct(array $config, $mock = false, $m = null){
        parent::__construct($config, $mock, $m);
        include($this->env);
        
        $this->urlbranch = '';
        if(isset($AMAZON_VERSION_REPORTS)) {
            $this->options['Version'] = $AMAZON_VERSION_REPORTS;
        }
    }
}
?>
