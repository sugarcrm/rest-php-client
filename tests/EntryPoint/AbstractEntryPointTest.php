<?php
/**
 * ©[2016] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace SugarAPI\SDK\Tests\Endpoint;

use SugarAPI\SDK\Request\DELETE;
use SugarAPI\SDK\Request\GET;
use SugarAPI\SDK\Request\GETFile;
use SugarAPI\SDK\Request\POST;
use SugarAPI\SDK\Request\POSTFile;
use SugarAPI\SDK\Request\PUT;
use SugarAPI\SDK\Response\JSON;
use SugarAPI\SDK\Tests\Stubs\Endpoint\EndpointStub;
use SugarAPI\SDK\Tests\Stubs\Response\ResponseStub;

/**
 * Class AbstractEndpointTest
 * @package SugarAPI\SDK\Tests\Endpoint
 * @coversDefaultClass SugarAPI\SDK\Endpoint\Abstracts\AbstractEndpoint
 * @group entrypoints
 */
class AbstractEndpointTest extends \PHPUnit_Framework_TestCase {

    public static function setUpBeforeClass()
    {
    }

    public static function tearDownAfterClass()
    {
    }

    protected $url = 'http://localhost/rest/v10/';
    protected $options = array('foo');
    protected $data = array(
        'foo' => 'bar'
    );

    public function setUp()
    {
        parent::setUp();
    }

    public function tearDown()
    {
        parent::tearDown();
    }

    /**
     * @return EndpointStub $Stub
     * @covers ::__construct
     * @group abstractEP
     */
    public function testConstructor(){
        $Stub = new EndpointStub($this->url);
        $this->assertEquals('http://localhost/rest/v10/$test',$Stub->getUrl());
        $this->assertEquals(array(),$Stub->getOptions());
        $this->assertEmpty($Stub->getData());
        $this->assertEmpty($Stub->getRequest());
        $this->assertEmpty($Stub->getResponse());

        unset($Stub);
        $Stub = new EndpointStub($this->url,$this->options);
        $this->assertEquals($this->url.'foo',$Stub->getUrl());
        $this->assertEquals($this->options,$Stub->getOptions());
        $this->assertEmpty($Stub->getData());
        $this->assertEmpty($Stub->getRequest());
        $this->assertEmpty($Stub->getResponse());
        
        return $Stub;
    }

    /**
     * @param EndpointStub $Stub
     * @return EndpointStub $Stub
     * @depends testConstructor
     * @covers ::setOptions
     * @covers ::getOptions
     * @covers ::getUrl
     * @covers ::configureUrl
     * @covers ::requiresOptions
     * @group abstractEP
     */
    public function testSetOptions($Stub){
        $Stub->setOptions(array());
        $this->assertEquals($this->url.'$test',$Stub->getUrl());
        $this->assertEquals(array(),$Stub->getOptions());
        $Stub->setOptions($this->options);
        $this->assertEquals($this->url.'foo',$Stub->getUrl());
        $this->assertEquals($this->options,$Stub->getOptions());
        
        return $Stub;
    }

    /**
     * @param EndpointStub $Stub
     * @return EndpointStub $Stub
     * @depends testSetOptions
     * @covers ::setData
     * @covers ::getData
     * @group abstractEP
     */
    public function testSetData($Stub){
        $Stub->setData(array());
        $this->assertEquals(array(),$Stub->getData());
        $Stub->setData('string');
        $this->assertEquals('string',$Stub->getData());
        $class = new \stdClass();
        $class->foo = 'bar';
        $Stub->setData($class);
        $this->assertEquals($class,$Stub->getData());
        unset($class);
        $Stub->setData($this->data);
        return $Stub;
    }

    /**
     * @param EndpointStub $Stub
     * @return EndpointStub $Stub
     * @depends testSetData
     * @covers ::setRequest
     * @covers ::getRequest
     * @group abstractEP
     */
    public function testSetRequest($Stub){
        $GET = new GET();
        $POST = new POST();
        $POSTFile = new POSTFile();
        $PUT = new PUT();
        $DELETE = new DELETE();
        $GETFile = new GETFile();
        $Stub->setRequest($POST);
        $this->assertEquals($POST,$Stub->getRequest());
        unset($POST);
        $Stub->setRequest($POSTFile);
        $this->assertEquals($POSTFile,$Stub->getRequest());
        unset($POSTFile);
        $Stub->setRequest($PUT);
        $this->assertEquals($PUT,$Stub->getRequest());
        unset($PUT);
        $Stub->setRequest($DELETE);
        $this->assertEquals($DELETE,$Stub->getRequest());
        unset($DELETE);
        $Stub->setRequest($GETFile);
        $this->assertEquals($GETFile,$Stub->getRequest());
        unset($GETFile);
        $Stub->setRequest($GET);
        $this->assertEquals($GET,$Stub->getRequest());
        return $Stub;
    }

    /**
     * @param EndpointStub $Stub
     * @return EndpointStub $Stub
     * @depends testSetRequest
     * @covers ::setAuth
     * @covers ::authRequired
     * @group abstractEP
     */
    public function testSetAuth($Stub){
        $Stub->setAuth('1234');
        $this->assertEquals(true,$Stub->authRequired());
        return $Stub;
    }

    /**
     * @param EndpointStub $Stub
     * @return EndpointStub $Stub
     * @depends testSetAuth
     * @covers ::execute
     * @covers ::configureRequest
     * @covers ::verifyUrl
     * @covers ::verifyData
     * @covers ::verifyRequiredData
     * @covers ::verifyDataType
     * @covers ::configureData
     * @covers ::configureDefaultData
     * @covers ::configureUrl
     * @covers ::configureResponse
     * @covers ::configureAuth
     * @covers ::setResponse
     * @group abstractEP
     */
    public function testExecute($Stub){
        $Stub->setResponse(new JSON($Stub->getRequest()->getCurlObject()));
        $Stub->execute();
        $this->assertEquals(array(
                                'foo' => 'bar',
                                'bar' => 'foo'
        ),$Stub->getData());
        $this->assertEquals($this->url.'foo?foo=bar&bar=foo',$Stub->getRequest()->getUrl());
        $this->assertEquals(array('Content-Type: application/json','OAuth-Token: 1234'),$Stub->getRequest()->getHeaders());
        $this->assertNotEmpty($Stub->getResponse()->getInfo());
        unset($Stub);

        $Stub = new EndpointStub($this->url,$this->options);
        $Stub->setRequest(new GET());
        $Stub->setAuth('1234');
        $Stub->execute($this->data);
        $this->assertEquals($this->url.'foo?foo=bar&bar=foo',$Stub->getRequest()->getUrl());
        $this->assertEquals(array('Content-Type: application/json','OAuth-Token: 1234'),$Stub->getRequest()->getHeaders());
        $this->assertEmpty($Stub->getResponse());

        return $Stub;
    }

    /**
     * @param EndpointStub $Stub
     * @return EndpointStub $Stub
     * @depends testExecute
     * @covers ::setResponse
     * @covers ::getResponse
     * @group abstractEP
     */
    public function testSetResponseAfterExecute($Stub){
        $Response = new JSON($Stub->getRequest()->getCurlObject(),$Stub->getRequest()->getCurlResponse());
        $Stub->setResponse($Response);
        $this->assertEquals($Response,$Stub->getResponse());
        $this->assertNotEmpty($Stub->getResponse()->getInfo());
    }

    /**
     * @covers ::setUrl
     * @group abstractEP
     */
    public function testSetUrl(){
        $Stub = new EndpointStub($this->url);
        $Stub->setUrl($this->url."foo");
        $this->assertEquals($this->url."foo",$Stub->getUrl());
        $Stub->setAuth('1234a');
        $Stub->setRequest(new GET());
        $Stub->execute($this->data);
        $this->assertEquals($this->url."foo",$Stub->getUrl());
        $this->assertEquals($this->url.'foo?foo=bar&bar=foo',$Stub->getRequest()->getURL());
    }

    /**
     * @covers ::verifyDataType
     * @expectedException SugarAPI\SDK\Exception\Endpoint\RequiredDataException
     * @expectedExceptionMessageRegExp /Valid DataType is array/
     * @group abstractEP
     */
    public function testInvalidDataType(){
        $Stub = new EndpointStub($this->url);
        $Stub->setOptions($this->options);
        $Stub->setRequest(new POST());
        $class = new \stdClass();
        $class->foo = 'bar';
        $Stub->setData($class);
        $Stub->execute();
    }

    /**
     * @covers ::verifyRequiredData
     * @expectedException SugarAPI\SDK\Exception\Endpoint\RequiredDataException
     * @expectedExceptionMessageRegExp /Missing data for/
     * @group abstractEP
     */
    public function testInvalidData(){
        $Stub = new EndpointStub($this->url);
        $Stub->setOptions($this->options);
        $Stub->setRequest(new POST());
        $Stub->setData(array());
        $Stub->execute();
    }

    /**
     * @covers ::execute
     * @expectedException SugarAPI\SDK\Exception\Endpoint\InvalidRequestException
     * @expectedExceptionMessageRegExp /Request property not configured/
     * @group abstractEP
     */
    public function testInvalidRequest(){
        $Stub = new EndpointStub($this->url);
        $Stub->setOptions($this->options);
        $Stub->setData($this->data);
        $Stub->execute();
    }

    /**
     * @covers ::verifyUrl
     * @expectedException SugarAPI\SDK\Exception\Endpoint\InvalidURLException
     * @expectedExceptionMessageRegExp /Configured URL is/
     * @group abstractEP
     */
    public function testInvalidURL(){
        $Stub = new EndpointStub($this->url);
        $Stub->setRequest(new POST());
        $Stub->execute($this->data);
    }


}
