<?php

namespace gugglegum\AmazonMWS\tests;

use gugglegum\AmazonMWS\AmazonOrderItemList;

/**
 * Generated by PHPUnit_SkeletonGenerator 1.2.0 on 2012-12-12 at 13:17:14.
 */
class AmazonOrderItemListTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var AmazonOrderItemList
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() {
        resetLog();
        $this->object = new AmazonOrderItemList(include(__DIR__ . '/../test-config.php'), null, true, null);
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown() {
        
    }
    
    public function testSetUp(){
        $obj = new AmazonOrderItemList(include(__DIR__ . '/../test-config.php'), '77', true, null);
        
        $o = $obj->getOptions();
        $this->assertArrayHasKey('AmazonOrderId',$o);
        $this->assertEquals('77', $o['AmazonOrderId']);
    }
    
    public function testSetUseToken(){
        $this->assertNull($this->object->setUseToken());
        $this->assertNull($this->object->setUseToken(true));
        $this->assertNull($this->object->setUseToken(false));
        $this->assertFalse($this->object->setUseToken('wrong'));
    }
    
    public function testSetOrderId(){
        $this->assertFalse($this->object->setOrderId(null)); //can't be nothing
        $this->assertFalse($this->object->setOrderId(array(5,7))); //not a valid value
        $this->assertNull($this->object->setOrderId(77));
        $this->assertNull($this->object->setOrderId('777'));
        $o = $this->object->getOptions();
        $this->assertArrayHasKey('AmazonOrderId',$o);
        $this->assertEquals('777',$o['AmazonOrderId']);
        
    }
    
    public function testFetchItems(){
        resetLog();
        $this->object->setOrderId('058-1233752-8214740');
        $this->object->setMock(true,'fetchOrderItems.xml'); //no token
        $this->assertNull($this->object->fetchItems());
        
        $o = $this->object->getOptions();
        $this->assertEquals('ListOrderItems',$o['Action']);
        
        $check = parseLog();
        $this->assertEquals('Single Mock File set: fetchOrderItems.xml',$check[1]);
        $this->assertEquals('Fetched Mock File: mock/fetchOrderItems.xml',$check[2]);
        
        $this->assertFalse($this->object->hasToken());
        
        return $this->object;
    }
    
    public function testFetchItemsBreak(){
        resetLog();
        $this->object->setOrderId('77');
        $this->object->setMock(true,array('countFeeds.xml','fetchOrderItems.xml'));
        $this->assertFalse($this->object->fetchItems()); //no results
        $this->assertNull($this->object->fetchItems()); //wrong ID
        
        $check = parseLog();
        $this->assertEquals('You just got throttled.',$check[3]);
        $this->assertEquals('You grabbed the wrong Order\'s items! - 77 =/= 058-1233752-8214740',$check[5]);
    }

    /**
     * @depends testFetchItems
     * @param AmazonOrderItemList $o
     */
    public function testOrderId($o) {
        $this->assertEquals('058-1233752-8214740', $o->getOrderId());

        $this->assertFalse($this->object->getOrderId()); //not fetched yet for this object
    }
    
    /**
     * @depends testFetchItems
     */
    public function testGetItems($o){
        $list = $o->getItems();
        $this->assertInternalType('array',$list);
        
        $x = array();
        $x1 = array();
        $x1['ASIN'] = 'BT0093TELA';
        $x1['SellerSKU'] = 'CBA_OTF_1';
        $x1['OrderItemId'] = '68828574383266';
        $x1['Title'] = 'Example item name';
        $x1['QuantityOrdered'] = '1';
        $x1['QuantityShipped'] = '1';
        $x1['BuyerCustomizedInfo'] = 'http://www.amazon.com';
        $x1['PointsGranted']['PointsNumber'] = '5';
        $x1['PointsGranted']['Amount'] = '2.50';
        $x1['PointsGranted']['CurrencyCode'] = 'USD';
        $x1['PriceDesignation'] = 'BusinessPrice';
        $x1['GiftMessageText'] = 'For you!';
        $x1['GiftWrapLevel'] = 'Classic';
        $x1['ItemPrice']['Amount'] = '25.99';
        $x1['ItemPrice']['CurrencyCode'] = 'USD';
        $x1['ShippingPrice']['Amount'] = '1.26';
        $x1['ShippingPrice']['CurrencyCode'] = 'USD';
        $x1['CODFee']['Amount'] = '10.00';
        $x1['CODFee']['CurrencyCode'] = 'USD';
        $x1['CODFeeDiscount']['Amount'] = '1.00';
        $x1['CODFeeDiscount']['CurrencyCode'] = 'USD';
        $x1['ItemTax'] = $x1['CODFeeDiscount'];
        $x1['ShippingTax'] = $x1['CODFeeDiscount'];
        $x1['GiftWrapTax'] = $x1['CODFeeDiscount'];
        $x1['ShippingDiscount'] = $x1['CODFeeDiscount'];
        $x1['PromotionDiscount'] = $x1['CODFeeDiscount'];
        $x1['GiftWrapPrice']['Amount'] = '1.99';
        $x1['GiftWrapPrice']['CurrencyCode'] = 'USD';
        $x[0] = $x1;
        $x2 = array();
        $x2['ASIN'] = 'BCTU1104UEFB';
        $x2['SellerSKU'] = 'CBA_OTF_5';
        $x2['OrderItemId'] = '79039765272157';
        $x2['Title'] = 'Example item name';
        $x2['QuantityOrdered'] = '2';
        $x2['ItemPrice']['Amount'] = '17.95';
        $x2['ItemPrice']['CurrencyCode'] = 'USD';
        $x2['PromotionIds'][0] = 'FREESHIP';
        $x[1] = $x2;
        
        $this->assertEquals($x, $list);
        
        $get = $o->getItems(0);
        $this->assertEquals($x1, $get);
        
        $this->assertFalse($this->object->getItems()); //not fetched yet for this object
    }
    
    /**
     * @depends testFetchItems
     */
    public function testGetASIN($o){
        $get = $o->getASIN(0);
        $this->assertEquals('BT0093TELA',$get);
        
        $this->assertFalse($o->getASIN('wrong')); //not number
        $this->assertFalse($this->object->getASIN()); //not fetched yet for this object
    }
    
    /**
     * @depends testFetchItems
     */
    public function testGetSellerSKU($o){
        $get = $o->getSellerSKU(0);
        $this->assertEquals('CBA_OTF_1',$get);
        
        $this->assertFalse($o->getSellerSKU('wrong')); //not number
        $this->assertFalse($this->object->getSellerSKU()); //not fetched yet for this object
    }
    
    /**
     * @depends testFetchItems
     */
    public function testGetOrderItemId($o){
        $get = $o->getOrderItemId(0);
        $this->assertEquals('68828574383266',$get);
        
        $this->assertFalse($o->getOrderItemId('wrong')); //not number
        $this->assertFalse($this->object->getOrderItemId()); //not fetched yet for this object
    }
    
    /**
     * @depends testFetchItems
     */
    public function testGetTitle($o){
        $get = $o->getTitle(0);
        $this->assertEquals('Example item name',$get);
        
        $this->assertFalse($o->getTitle('wrong')); //not number
        $this->assertFalse($this->object->getTitle()); //not fetched yet for this object
    }
    
    /**
     * @depends testFetchItems
     */
    public function testGetQuantityOrdered($o){
        $get = $o->getQuantityOrdered(0);
        $this->assertEquals('1',$get);
        
        $this->assertFalse($o->getQuantityOrdered('wrong')); //not number
        $this->assertFalse($this->object->getQuantityOrdered()); //not fetched yet for this object
    }
    
    /**
     * @depends testFetchItems
     */
    public function testGetQuantityShipped($o){
        $get = $o->getQuantityShipped(0);
        $this->assertEquals('1',$get);
        
        $this->assertFalse($o->getQuantityShipped('wrong')); //not number
        $this->assertFalse($this->object->getQuantityShipped()); //not fetched yet for this object
    }

    /**
     * @depends testFetchItems
     * @param AmazonOrderItemList $o
     */
    public function testGetCustomizedInfo($o) {
        $this->assertEquals('http://www.amazon.com', $o->getCustomizedInfo(0));
        $this->assertEquals($o->getCustomizedInfo(0), $o->getCustomizedInfo());

        $this->assertFalse($o->getCustomizedInfo('wrong')); //not number
        $this->assertFalse($this->object->getCustomizedInfo()); //not fetched yet for this object
    }

    /**
     * @depends testFetchItems
     * @param AmazonOrderItemList $o
     */
    public function testGetPointsGranted($o) {
        $x = array();
        $x['PointsNumber'] = '5';
        $x['Amount'] = '2.50';
        $x['CurrencyCode'] = 'USD';
        $this->assertEquals($x, $o->getPointsGranted(0));
        $this->assertEquals($o->getPointsGranted(0), $o->getPointsGranted());

        $this->assertFalse($o->getPointsGranted('wrong')); //not number
        $this->assertFalse($this->object->getPointsGranted()); //not fetched yet for this object
    }

    /**
     * @depends testFetchItems
     * @param AmazonOrderItemList $o
     */
    public function testGetPriceDesignation($o) {
        $this->assertEquals('BusinessPrice', $o->getPriceDesignation(0));
        $this->assertEquals($o->getPriceDesignation(0), $o->getPriceDesignation());

        $this->assertFalse($o->getPriceDesignation('wrong')); //not number
        $this->assertFalse($this->object->getPriceDesignation()); //not fetched yet for this object
    }
    
    /**
     * @depends testFetchItems
     */
    public function testGetPercentShipped($o){
        $get = $o->getPercentShipped(0);
        $this->assertEquals('1',$get);
        
        $this->assertFalse($o->getPercentShipped('wrong')); //not number
        $this->assertFalse($o->getPercentShipped(1)); //shipped not set
        $this->assertFalse($this->object->getPercentShipped()); //not fetched yet for this object
    }
    
    /**
     * @depends testFetchItems
     */
    public function testGetGiftMessageText($o){
        $get = $o->getGiftMessageText(0);
        $this->assertEquals('For you!',$get);
        
        $this->assertFalse($o->getGiftMessageText('wrong')); //not number
        $this->assertFalse($this->object->getGiftMessageText()); //not fetched yet for this object
    }
    
    /**
     * @depends testFetchItems
     */
    public function testGetGiftWrapLevel($o){
        $get = $o->getGiftWrapLevel(0);
        $this->assertEquals('Classic',$get);
        
        $this->assertFalse($o->getGiftWrapLevel('wrong')); //not number
        $this->assertFalse($this->object->getGiftWrapLevel()); //not fetched yet for this object
    }
    
    /**
     * @depends testFetchItems
     */
    public function testGetItemPrice($o){
        $get = $o->getItemPrice(0);
        $x = array();
        $x['Amount'] = '25.99';
        $x['CurrencyCode'] = 'USD';
        $this->assertEquals($x,$get);
        
        $a = $o->getItemPrice(0,true);
        $this->assertEquals($x['Amount'],$a);
        
        $this->assertFalse($o->getItemPrice('wrong')); //not number
        $this->assertFalse($this->object->getItemPrice()); //not fetched yet for this object
    }
    
    /**
     * @depends testFetchItems
     */
    public function testGetShippingPrice($o){
        $get = $o->getShippingPrice(0);
        $x = array();
        $x['Amount'] = '1.26';
        $x['CurrencyCode'] = 'USD';
        $this->assertEquals($x,$get);
        
        $a = $o->getShippingPrice(0,true);
        $this->assertEquals($x['Amount'],$a);
        
        $this->assertFalse($o->getShippingPrice('wrong')); //not number
        $this->assertFalse($this->object->getShippingPrice()); //not fetched yet for this object
    }
    
    /**
     * @depends testFetchItems
     */
    public function testGetGiftWrapPrice($o){
        $get = $o->getGiftWrapPrice(0);
        $x = array();
        $x['Amount'] = '1.99';
        $x['CurrencyCode'] = 'USD';
        $this->assertEquals($x,$get);
        
        $a = $o->getGiftWrapPrice(0,true);
        $this->assertEquals($x['Amount'],$a);
        
        $this->assertFalse($o->getGiftWrapPrice('wrong')); //not number
        $this->assertFalse($this->object->getGiftWrapPrice()); //not fetched yet for this object
    }
    
    /**
     * @depends testFetchItems
     */
    public function testGetItemTax($o){
        $get = $o->getItemTax(0);
        $x = array();
        $x['Amount'] = '1.00';
        $x['CurrencyCode'] = 'USD';
        $this->assertEquals($x,$get);
        
        $a = $o->getItemTax(0,true);
        $this->assertEquals($x['Amount'],$a);
        
        $this->assertFalse($o->getItemTax('wrong')); //not number
        $this->assertFalse($this->object->getItemTax()); //not fetched yet for this object
    }
    
    /**
     * @depends testFetchItems
     */
    public function testGetShippingTax($o){
        $get = $o->getShippingTax(0);
        $x = array();
        $x['Amount'] = '1.00';
        $x['CurrencyCode'] = 'USD';
        $this->assertEquals($x,$get);
        
        $a = $o->getShippingTax(0,true);
        $this->assertEquals($x['Amount'],$a);
        
        $this->assertFalse($o->getShippingTax('wrong')); //not number
        $this->assertFalse($this->object->getShippingTax()); //not fetched yet for this object
    }
    
    /**
     * @depends testFetchItems
     */
    public function testGetGiftWrapTax($o){
        $get = $o->getGiftWrapTax(0);
        $x = array();
        $x['Amount'] = '1.00';
        $x['CurrencyCode'] = 'USD';
        $this->assertEquals($x,$get);
        
        $a = $o->getGiftWrapTax(0,true);
        $this->assertEquals($x['Amount'],$a);
        
        $this->assertFalse($o->getGiftWrapTax('wrong')); //not number
        $this->assertFalse($this->object->getGiftWrapTax()); //not fetched yet for this object
    }
    
    /**
     * @depends testFetchItems
     */
    public function testGetShippingDiscount($o){
        $get = $o->getShippingDiscount(0);
        $x = array();
        $x['Amount'] = '1.00';
        $x['CurrencyCode'] = 'USD';
        $this->assertEquals($x,$get);
        
        $a = $o->getShippingDiscount(0,true);
        $this->assertEquals($x['Amount'],$a);
        
        $this->assertFalse($o->getShippingDiscount('wrong')); //not number
        $this->assertFalse($this->object->getShippingDiscount()); //not fetched yet for this object
    }
    
    /**
     * @depends testFetchItems
     */
    public function testGetPromotionDiscount($o){
        $get = $o->getPromotionDiscount(0);
        $x = array();
        $x['Amount'] = '1.00';
        $x['CurrencyCode'] = 'USD';
        $this->assertEquals($x,$get);
        
        $a = $o->getPromotionDiscount(0,true);
        $this->assertEquals($x['Amount'],$a);
        
        $this->assertFalse($o->getPromotionDiscount('wrong')); //not number
        $this->assertFalse($this->object->getPromotionDiscount()); //not fetched yet for this object
    }
    
    /**
     * @depends testFetchItems
     */
    public function testGetPromotionIds($o){
        $getOne = $o->getPromotionIds(1);
        $x = array();
        $x[0] = 'FREESHIP';
        $this->assertEquals($x,$getOne);
        
        $get = $o->getPromotionIds(1,0);
        $this->assertEquals($x[0],$get);
        
        $this->assertFalse($o->getPromotionIds('wrong')); //not number
        $this->assertFalse($this->object->getPromotionIds()); //not fetched yet for this object
    }
    
    public function testFetchOrderItemsToken1(){
        resetLog();
        $this->object->setMock(true,'fetchOrderItemsToken.xml'); //no token
        $this->object->setOrderId('058-1233752-8214740');
        
        //without using token
        $this->assertNull($this->object->fetchItems());
        $check = parseLog();
        $this->assertEquals('Single Mock File set: fetchOrderItemsToken.xml',$check[1]);
        $this->assertEquals('Fetched Mock File: mock/fetchOrderItemsToken.xml',$check[2]);
        
        $this->assertTrue($this->object->hasToken());
        $o = $this->object->getOptions();
        $this->assertEquals('ListOrderItems',$o['Action']);
        $r = $this->object->getItems();
        $this->assertArrayHasKey(0,$r);
        $this->assertEquals('CBA_OTF_1',$r[0]['SellerSKU']);
        $this->assertArrayNotHasKey(1,$r);
    }
    
    public function testFetchOrderItemsToken2(){
        resetLog();
        $this->object->setMock(true,array('fetchOrderItemsToken.xml','fetchOrderItemsToken2.xml'));
        $this->object->setOrderId('058-1233752-8214740');
        
        //with using token
        $this->object->setUseToken();
        $this->assertNull($this->object->fetchItems());
        $check = parseLog();
        $this->assertEquals('Mock files array set.',$check[1]);
        $this->assertEquals('Fetched Mock File: mock/fetchOrderItemsToken.xml',$check[2]);
        $this->assertEquals('Recursively fetching more items',$check[3]);
        $this->assertEquals('Fetched Mock File: mock/fetchOrderItemsToken2.xml',$check[4]);
        $this->assertFalse($this->object->hasToken());
        $o = $this->object->getOptions();
        $this->assertEquals('ListOrderItemsByNextToken',$o['Action']);
        $this->assertArrayNotHasKey('AmazonOrderId',$o);
        $r = $this->object->getItems();
        $this->assertArrayHasKey(0,$r);
        $this->assertArrayHasKey(1,$r);
        $this->assertEquals('CBA_OTF_1',$r[0]['SellerSKU']);
        $this->assertEquals('CBA_OTF_5',$r[1]['SellerSKU']);
        $this->assertEquals(2,count($r));
        $this->assertNotEquals($r[0],$r[1]);
    }
    
}

require_once('helperFunctions.php');
