<?php

namespace gugglegum\AmazonMWS\tests;

use gugglegum\AmazonMWS\AmazonFeedResult;

/**
 * Generated by PHPUnit_SkeletonGenerator 1.2.0 on 2012-12-12 at 13:17:14.
 */
class AmazonFeedResultTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var AmazonFeedResult
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() {
        resetLog();
        $this->object = new AmazonFeedResult(include(__DIR__ . '/../test-config.php'), null, true, null);
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown() {
        
    }
    
    public function testSetUp(){
        $obj = new AmazonFeedResult(include(__DIR__ . '/../test-config.php'), 77, true, null);
        
        $o = $obj->getOptions();
        $this->assertArrayHasKey('FeedSubmissionId',$o);
        $this->assertEquals(77, $o['FeedSubmissionId']);
    }
    
    public function testSetFeedId(){
        $ok = $this->object->setFeedId(77);
        $this->assertNull($ok);
        $o = $this->object->getOptions();
        $this->assertArrayHasKey('FeedSubmissionId',$o);
        $this->assertEquals(77, $o['FeedSubmissionId']);
        $this->assertFalse($this->object->setFeedId('string'));
    }
    
    public function testFetchFeedResult(){
        resetLog();
        $this->object->setMock(true,'fetchFeedResult.xml');
        $this->assertFalse($this->object->fetchFeedResult()); //no ID set yet
        $this->object->setFeedId(77);
        $ok = $this->object->fetchFeedResult();
        $this->assertNull($ok);
        
        $check = parseLog();
        $this->assertEquals('Single Mock File set: fetchFeedResult.xml',$check[1]);
        $this->assertEquals('Feed Submission ID must be set in order to fetch it!',$check[2]);
        $this->assertEquals('Fetched Mock File: mock/fetchFeedResult.xml',$check[3]);
        
        return $this->object;
    }
    
    /**
     * @depends testFetchFeedResult
     */
    public function testSaveFeed($o){
        resetLog();
        $this->assertFalse($this->object->saveFeed('mock/saveFeed.xml')); //nothing yet
        $o->saveFeed(__DIR__ . '/../mock/saveFeed.xml');
        $check = parseLog();
        $this->assertEquals('Successfully saved feed #77 at '.__DIR__.'/../mock/saveFeed.xml',$check[0]);
    }
    
}

require_once('helperFunctions.php');
