<?php

/**
 * Generated by PHPUnit_SkeletonGenerator 1.2.0 on 2012-12-12 at 13:17:14.
 */
class AmazonReportRequestListTest extends PHPUnit_Framework_TestCase {

    /**
     * @var AmazonReportRequestList
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() {
        resetLog();
        $this->object = new AmazonReportRequestList(include(__DIR__.'/../../test-config.php'), true, null);
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown() {
        
    }
    
    public function testSetUseToken(){
        $this->assertNull($this->object->setUseToken());
        $this->assertNull($this->object->setUseToken(true));
        $this->assertNull($this->object->setUseToken(false));
        $this->assertFalse($this->object->setUseToken('wrong'));
    }
    
    public function testSetRequestIds(){
        $this->assertFalse($this->object->setRequestIds(null)); //can't be nothing
        $this->assertFalse($this->object->setRequestIds(5)); //can't be an int
        
        $list = array('One','Two','Three');
        $this->assertNull($this->object->setRequestIds($list));
        
        $o = $this->object->getOptions();
        $this->assertArrayHasKey('ReportRequestIdList.Id.1',$o);
        $this->assertEquals('One',$o['ReportRequestIdList.Id.1']);
        $this->assertArrayHasKey('ReportRequestIdList.Id.2',$o);
        $this->assertEquals('Two',$o['ReportRequestIdList.Id.2']);
        $this->assertArrayHasKey('ReportRequestIdList.Id.3',$o);
        $this->assertEquals('Three',$o['ReportRequestIdList.Id.3']);
        
        $this->assertNull($this->object->setRequestIds('Four')); //will cause reset
        $o2 = $this->object->getOptions();
        $this->assertArrayHasKey('ReportRequestIdList.Id.1',$o2);
        $this->assertEquals('Four',$o2['ReportRequestIdList.Id.1']);
        $this->assertArrayNotHasKey('ReportRequestIdList.Id.2',$o2);
        $this->assertArrayNotHasKey('ReportRequestIdList.Id.3',$o2);
        
        $this->object->resetRequestIds();
        $o3 = $this->object->getOptions();
        $this->assertArrayNotHasKey('ReportRequestIdList.Id.1',$o3);
    }
    
    public function testSetReportTypes(){
        $this->assertFalse($this->object->setReportTypes(null)); //can't be nothing
        $this->assertFalse($this->object->setReportTypes(5)); //can't be an int
        
        $list = array('One','Two','Three');
        $this->assertNull($this->object->setReportTypes($list));
        
        $o = $this->object->getOptions();
        $this->assertArrayHasKey('ReportTypeList.Type.1',$o);
        $this->assertEquals('One',$o['ReportTypeList.Type.1']);
        $this->assertArrayHasKey('ReportTypeList.Type.2',$o);
        $this->assertEquals('Two',$o['ReportTypeList.Type.2']);
        $this->assertArrayHasKey('ReportTypeList.Type.3',$o);
        $this->assertEquals('Three',$o['ReportTypeList.Type.3']);
        
        $this->assertNull($this->object->setReportTypes('Four')); //will cause reset
        $o2 = $this->object->getOptions();
        $this->assertArrayHasKey('ReportTypeList.Type.1',$o2);
        $this->assertEquals('Four',$o2['ReportTypeList.Type.1']);
        $this->assertArrayNotHasKey('ReportTypeList.Type.2',$o2);
        $this->assertArrayNotHasKey('ReportTypeList.Type.3',$o2);
        
        $this->object->resetReportTypes();
        $o3 = $this->object->getOptions();
        $this->assertArrayNotHasKey('ReportTypeList.Type.1',$o3);
    }
    
    public function testSetReportStatuses(){
        $this->assertFalse($this->object->setReportStatuses(null)); //can't be nothing
        $this->assertFalse($this->object->setReportStatuses(5)); //can't be an int
        
        $list = array('One','Two','Three');
        $this->assertNull($this->object->setReportStatuses($list));
        
        $o = $this->object->getOptions();
        $this->assertArrayHasKey('ReportProcessingStatusList.Status.1',$o);
        $this->assertEquals('One',$o['ReportProcessingStatusList.Status.1']);
        $this->assertArrayHasKey('ReportProcessingStatusList.Status.2',$o);
        $this->assertEquals('Two',$o['ReportProcessingStatusList.Status.2']);
        $this->assertArrayHasKey('ReportProcessingStatusList.Status.3',$o);
        $this->assertEquals('Three',$o['ReportProcessingStatusList.Status.3']);
        
        $this->assertNull($this->object->setReportStatuses('Four')); //will cause reset
        $o2 = $this->object->getOptions();
        $this->assertArrayHasKey('ReportProcessingStatusList.Status.1',$o2);
        $this->assertEquals('Four',$o2['ReportProcessingStatusList.Status.1']);
        $this->assertArrayNotHasKey('ReportProcessingStatusList.Status.2',$o2);
        $this->assertArrayNotHasKey('ReportProcessingStatusList.Status.3',$o2);
        
        $this->object->resetReportStatuses();
        $o3 = $this->object->getOptions();
        $this->assertArrayNotHasKey('ReportProcessingStatusList.Status.1',$o3);
    }
    
    public function testSetMaxCount(){
        $this->assertFalse($this->object->setMaxCount(null)); //can't be nothing
        $this->assertFalse($this->object->setMaxCount(9.75)); //can't be decimal
        $this->assertFalse($this->object->setMaxCount('75')); //can't be a string
        $this->assertFalse($this->object->setMaxCount(array(5,7))); //not a valid value
        $this->assertFalse($this->object->setMaxCount('banana')); //what are you even doing
        $this->assertNull($this->object->setMaxCount(77));
        $o = $this->object->getOptions();
        $this->assertArrayHasKey('MaxCount',$o);
        $this->assertEquals(77,$o['MaxCount']);
    }
    
    /**
    * @return array
    */
    public function timeProvider() {
        return array(
            array(null, null, false, false), //nothing given, so no change
            array(true, true, false, false), //not strings or numbers
            array('', '', false, false), //strings, but empty
            array('-1 min', null, true, false), //one set
            array(null, '-1 min', false, true), //other set
            array('-1 min', '-1 min', true, true), //both set
        );
    }
    
    /**
     * @dataProvider timeProvider
     */
    public function testSetTimeLimits($a, $b, $c, $d){
        $this->object->setTimeLimits($a,$b);
        $o = $this->object->getOptions();
        if ($c){
            $this->assertArrayHasKey('RequestedFromDate',$o);
            $this->assertStringMatchesFormat('%d-%d-%dT%d:%d:%d%i',$o['RequestedFromDate']);
        } else {
            $this->assertArrayNotHasKey('RequestedFromDate',$o);
        }
        
        if ($d){
            $this->assertArrayHasKey('RequestedToDate',$o);
            $this->assertStringMatchesFormat('%d-%d-%dT%d:%d:%d%i',$o['RequestedToDate']);
        } else {
            $this->assertArrayNotHasKey('RequestedToDate',$o);
        }
    }
    
    public function testResetTimeLimit(){
        $this->object->setTimeLimits('-1 min','-1 min');
        $o = $this->object->getOptions();
        $this->assertArrayHasKey('RequestedFromDate',$o);
        $this->assertArrayHasKey('RequestedToDate',$o);
        
        $this->object->resetTimeLimits();
        $check = $this->object->getOptions();
        $this->assertArrayNotHasKey('RequestedFromDate',$check);
        $this->assertArrayNotHasKey('RequestedToDate',$check);
    }
    
    public function testFetchRequestList(){
        resetLog();
        $this->object->setMock(true,'fetchReportRequestList.xml'); //no token
        $this->assertNull($this->object->fetchRequestList());
        
        $o = $this->object->getOptions();
        $this->assertEquals('GetReportRequestList',$o['Action']);
        
        $check = parseLog();
        $this->assertEquals('Single Mock File set: fetchReportRequestList.xml',$check[1]);
        $this->assertEquals('Fetched Mock File: mock/fetchReportRequestList.xml',$check[2]);
        
        $this->assertFalse($this->object->hasToken());
        
        return $this->object;
    }
    
    public function testFetchRequestListToken1(){
        resetLog();
        $this->object->setMock(true,'fetchReportRequestListToken.xml'); //no token
        
        //without using token
        $this->assertNull($this->object->fetchRequestList());
        $check = parseLog();
        $this->assertEquals('Single Mock File set: fetchReportRequestListToken.xml',$check[1]);
        $this->assertEquals('Fetched Mock File: mock/fetchReportRequestListToken.xml',$check[2]);
        
        $this->assertTrue($this->object->hasToken());
        $o = $this->object->getOptions();
        $this->assertEquals('GetReportRequestList',$o['Action']);
        $r = $this->object->getList();
        $this->assertArrayHasKey(0,$r);
        $this->assertEquals(1,count($r));
        $this->assertInternalType('array',$r[0]);
        $this->assertArrayNotHasKey(1,$r);
    }
    
    public function testFetchRequestListToken2(){
        resetLog();
        $this->object->setMock(true,array('fetchReportRequestListToken.xml','fetchReportRequestListToken2.xml'));
        
        //with using token
        $this->object->setUseToken();
        $this->assertNull($this->object->fetchRequestList());
        $check = parseLog();
        $this->assertEquals('Mock files array set.',$check[1]);
        $this->assertEquals('Fetched Mock File: mock/fetchReportRequestListToken.xml',$check[2]);
        $this->assertEquals('Recursively fetching more Report Requests',$check[3]);
        $this->assertEquals('Fetched Mock File: mock/fetchReportRequestListToken2.xml',$check[4]);
        $this->assertFalse($this->object->hasToken());
        $o = $this->object->getOptions();
        $this->assertEquals('GetReportRequestListByNextToken',$o['Action']);
        $r = $this->object->getList();
        $this->assertArrayHasKey(0,$r);
        $this->assertArrayHasKey(1,$r);
        $this->assertEquals(2,count($r));
        $this->assertInternalType('array',$r[0]);
        $this->assertInternalType('array',$r[1]);
        $this->assertNotEquals($r[0],$r[1]);
    }
    
    public function testCancelRequests(){
        resetLog();
        $this->object->setMock(true,'cancelRequests.xml'); //no token
        $this->assertNull($this->object->cancelRequests());
        
        $o = $this->object->getOptions();
        $this->assertEquals('CancelReportRequests',$o['Action']);
        
        $check = parseLog();
        $this->assertEquals('Single Mock File set: cancelRequests.xml',$check[1]);
        $this->assertEquals('Fetched Mock File: mock/cancelRequests.xml',$check[2]);
        $this->assertEquals('Successfully canceled 10 report requests.',$check[3]);
    }
    
    /**
     * @depends testFetchRequestList
     */
    public function testGetRequestId($o){
        $get = $o->getRequestId(0);
        $this->assertEquals('2291326454',$get);
        
        $this->assertFalse($o->getRequestId('wrong')); //not number
        $this->assertFalse($o->getRequestId(1.5)); //no decimals
        $this->assertFalse($this->object->getRequestId()); //not fetched yet for this object
    }
    
    /**
     * @depends testFetchRequestList
     */
    public function testGetReportType($o){
        $get = $o->getReportType(0);
        $this->assertEquals('_GET_MERCHANT_LISTINGS_DATA_',$get);
        
        $this->assertFalse($o->getReportType('wrong')); //not number
        $this->assertFalse($o->getReportType(1.5)); //no decimals
        $this->assertFalse($this->object->getReportType()); //not fetched yet for this object
    }
    
    /**
     * @depends testFetchRequestList
     */
    public function testGetStartDate($o){
        $get = $o->getStartDate(0);
        $this->assertEquals('2011-01-21T02:10:39+00:00',$get);
        
        $this->assertFalse($o->getStartDate('wrong')); //not number
        $this->assertFalse($o->getStartDate(1.5)); //no decimals
        $this->assertFalse($this->object->getStartDate()); //not fetched yet for this object
    }
    
    /**
     * @depends testFetchRequestList
     */
    public function testGetEndDate($o){
        $get = $o->getEndDate(0);
        $this->assertEquals('2011-02-13T02:10:39+00:00',$get);
        
        $this->assertFalse($o->getEndDate('wrong')); //not number
        $this->assertFalse($o->getEndDate(1.5)); //no decimals
        $this->assertFalse($this->object->getEndDate()); //not fetched yet for this object
    }
    
    /**
     * @depends testFetchRequestList
     */
    public function testGetIsScheduled($o){
        $get = $o->getIsScheduled(0);
        $this->assertEquals('false',$get);
        
        $this->assertFalse($o->getIsScheduled('wrong')); //not number
        $this->assertFalse($o->getIsScheduled(1.5)); //no decimals
        $this->assertFalse($this->object->getIsScheduled()); //not fetched yet for this object
    }
    
    /**
     * @depends testFetchRequestList
     */
    public function testGetSubmittedDate($o){
        $get = $o->getSubmittedDate(0);
        $this->assertEquals('2011-02-17T23:44:09+00:00',$get);
        
        $this->assertFalse($o->getSubmittedDate('wrong')); //not number
        $this->assertFalse($o->getSubmittedDate(1.5)); //no decimals
        $this->assertFalse($this->object->getSubmittedDate()); //not fetched yet for this object
    }
    
    /**
     * @depends testFetchRequestList
     */
    public function testGetStatus($o){
        $get = $o->getStatus(0);
        $this->assertEquals('_DONE_',$get);
        
        $this->assertFalse($o->getStatus('wrong')); //not number
        $this->assertFalse($o->getStatus(1.5)); //no decimals
        $this->assertFalse($this->object->getStatus()); //not fetched yet for this object
    }
    
    /**
     * @depends testFetchRequestList
     */
    public function testGetReportId($o){
        $get = $o->getReportId(0);
        $this->assertEquals('3538561173',$get);
        
        $this->assertFalse($o->getReportId('wrong')); //not number
        $this->assertFalse($o->getReportId(1.5)); //no decimals
        $this->assertFalse($this->object->getReportId()); //not fetched yet for this object
    }
    
    /**
     * @depends testFetchRequestList
     */
    public function testGetDateProcessingStarted($o){
        $get = $o->getDateProcessingStarted(0);
        $this->assertEquals('2011-02-17T23:44:43+00:00',$get);
        
        $this->assertFalse($o->getDateProcessingStarted('wrong')); //not number
        $this->assertFalse($o->getDateProcessingStarted(1.5)); //no decimals
        $this->assertFalse($this->object->getDateProcessingStarted()); //not fetched yet for this object
    }
    
    /**
     * @depends testFetchRequestList
     */
    public function testgetDateCompleted($o){
        $get = $o->getDateCompleted(0);
        $this->assertEquals('2011-02-17T23:44:48+00:00',$get);
        
        $this->assertFalse($o->getDateCompleted('wrong')); //not number
        $this->assertFalse($o->getDateCompleted(1.5)); //no decimals
        $this->assertFalse($this->object->getDateCompleted()); //not fetched yet for this object
    }
    
    /**
     * @depends testFetchRequestList
     */
    public function testGetList($o){
        $x = array();
        $x1 = array();
        $x1['ReportRequestId'] = '2291326454';
        $x1['ReportType'] = '_GET_MERCHANT_LISTINGS_DATA_';
        $x1['StartDate'] = '2011-01-21T02:10:39+00:00';
        $x1['EndDate'] = '2011-02-13T02:10:39+00:00';
        $x1['Scheduled'] = 'false';
        $x1['SubmittedDate'] = '2011-02-17T23:44:09+00:00';
        $x1['ReportProcessingStatus'] = '_DONE_';
        $x1['GeneratedReportId'] = '3538561173';
        $x1['StartedProcessingDate'] = '2011-02-17T23:44:43+00:00';
        $x1['CompletedDate'] = '2011-02-17T23:44:48+00:00';
        $x[0] = $x1;
        
        $this->assertEquals($x,$o->getList());
        $this->assertEquals($x1,$o->getList(0));
        
        $this->assertFalse($this->object->getList()); //not fetched yet for this object
    }
    
    public function testFetchCount(){
        resetLog();
        $this->object->setRequestIds('123456');
        $this->object->setMaxCount(77);
        $this->object->setMock(true,'fetchReportRequestCount.xml');
        $this->assertNull($this->object->fetchCount());
        
        $o = $this->object->getOptions();
        $this->assertEquals('GetReportRequestCount',$o['Action']);
        $this->assertArrayNotHasKey('ReportRequestIdList.Id.1',$o);
        $this->assertArrayNotHasKey('MaxCount',$o);
        
        $check = parseLog();
        $this->assertEquals('Single Mock File set: fetchReportRequestCount.xml',$check[1]);
        $this->assertEquals('Fetched Mock File: mock/fetchReportRequestCount.xml',$check[2]);
        
        $this->assertFalse($this->object->hasToken());
        
        return $this->object;
    }
    
    /**
     * @depends testFetchCount
     */
    public function testGetCount($o){
        $get = $o->getCount();
        $this->assertEquals('1276',$get);
        
        $this->assertFalse($this->object->getCount()); //not fetched yet for this object
    }
    
}

require_once('helperFunctions.php');
