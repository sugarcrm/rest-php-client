<?php
/**
 * ©[2016] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace SugarAPI\SDK\Tests\Request;
use SugarAPI\SDK\Tests\Stubs\Request\RequestStub;

/**
 * Class AbstractResponseTest
 * @package SugarAPI\SDK\Tests\Request\AbstractRequestTest
 * @coversDefaultClass SugarAPI\SDK\Request\Abstracts\AbstractRequest
 * @group requests
 */
class AbstractRequestTest extends \PHPUnit_Framework_TestCase {

    public static function setUpBeforeClass()
    {
    }

    public static function tearDownAfterClass()
    {
    }

    protected $url = 'localhost';
    protected $body = array(
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
     * @covers ::__construct
     * @covers ::start
     * @covers ::setOption
     * @covers ::getCurlStatus
     * @covers ::getOptions
     * @covers ::getCurlStatus
     * @covers ::getCurlObject
     * @group abstractRequest
     * @return RequestStub
     */
    public function testConstructor(){
        $Stub = new RequestStub();
        $this->assertEmpty($Stub->getBody());
        $this->assertEmpty($Stub->getType());
        $this->assertEmpty($Stub->getURL());
        $this->assertEquals('initialized',$Stub->getCurlStatus());
        $this->assertNotEmpty($Stub->getCurlObject());
        $this->assertEquals('',$Stub->getType());
        $this->assertEmpty($Stub->getCurlResponse());
        $this->assertEmpty($Stub->getHeaders());
        $options = $Stub->getOptions();
        $this->assertEquals('SugarAPI-SDK-PHP',$options[CURLOPT_USERAGENT]);
        $this->assertEquals(TRUE,$options[CURLOPT_HEADER]);
        $this->assertEquals(CURL_HTTP_VERSION_1_0,$options[CURLOPT_HTTP_VERSION]);
        $this->assertEquals(FALSE,$options[CURLOPT_SSL_VERIFYPEER]);
        $this->assertEquals(FALSE,$options[CURLOPT_FOLLOWLOCATION]);
        unset($Stub);

        $Stub = new RequestStub($this->url);
        $this->assertEmpty($Stub->getBody());
        $this->assertEmpty($Stub->getType());
        $this->assertEquals('localhost',$Stub->getURL());
        $this->assertEquals('initialized',$Stub->getCurlStatus());
        $this->assertNotEmpty($Stub->getCurlObject());
        $this->assertEquals('',$Stub->getType());
        $this->assertEmpty($Stub->getCurlResponse());
        $this->assertNotEmpty($Stub->getOptions());
        $this->assertEmpty($Stub->getHeaders());
        $options = $Stub->getOptions();
        $this->assertEquals('SugarAPI-SDK-PHP',$options[CURLOPT_USERAGENT]);
        $this->assertEquals(TRUE,$options[CURLOPT_HEADER]);
        $this->assertEquals(CURL_HTTP_VERSION_1_0,$options[CURLOPT_HTTP_VERSION]);
        $this->assertEquals(FALSE,$options[CURLOPT_SSL_VERIFYPEER]);
        $this->assertEquals(FALSE,$options[CURLOPT_FOLLOWLOCATION]);

        return $Stub;
    }

    /**
     * @param $Stub RequestStub
     * @depends testConstructor
     * @covers ::setURL
     * @covers ::getURL
     * @group abstractRequest
     * @return RequestStub
     */
    public function testSetUrl($Stub){
        $Stub->setURL("https://local.foo.bar");
        $this->assertEquals("https://local.foo.bar",$Stub->getURL());
        $Stub->setURL("http://local.foo");
        $this->assertEquals("http://local.foo",$Stub->getURL());
        $Stub->setURL("192.168.1.20");
        $this->assertEquals("192.168.1.20",$Stub->getURL());
        return $Stub;
    }

    /**
     * @param $Stub RequestStub
     * @depends testSetUrl
     * @covers ::setType
     * @covers ::getType
     * @covers ::configureType
     * @group abstractRequest
     * @return RequestStub
     */
    public function testSetType($Stub){
        $Stub->setType('get');
        $this->assertEquals("GET",$Stub->getType());
        unset($Stub);

        $Stub = new RequestStub($this->url);
        $Stub->setType('Post');
        $this->assertEquals("POST",$Stub->getType());
        $options = $Stub->getOptions();
        $this->assertEquals(true,$options[CURLOPT_POST]);
        unset($Stub);

        $Stub = new RequestStub($this->url);
        $Stub->setType('PUt');
        $this->assertEquals("PUT",$Stub->getType());
        $options = $Stub->getOptions();
        $this->assertEquals("PUT",$options[CURLOPT_CUSTOMREQUEST]);
        unset($Stub);

        $Stub = new RequestStub($this->url);
        $Stub->setType('DeLeTE');
        $this->assertEquals("DELETE",$Stub->getType());
        $options = $Stub->getOptions();
        $this->assertEquals("DELETE",$options[CURLOPT_CUSTOMREQUEST]);
        return $Stub;
    }

    /**
     * @param $Stub RequestStub
     * @depends testSetType
     * @covers ::setBody
     * @covers ::getBody
     * @group abstractRequest
     * @return RequestStub
     */
    public function testSetBody($Stub){
        $Stub->setBody($this->body);
        $this->assertEquals($this->body,$Stub->getBody());
        $options = $Stub->getOptions();
        $this->assertEquals($this->body,$options[CURLOPT_POSTFIELDS]);
        return $Stub;
    }

    /**
     * @param $Stub RequestStub
     * @depends testSetBody
     * @covers ::setHeaders
     * @covers ::addHeader
     * @covers ::getHeaders
     * @group abstractRequest
     * @return RequestStub
     */
    public function testSetHeaders($Stub){
        $headers = array(
            'OAuth-Token: 12345a',
            'Content-Type: application/json'
        );
        $Stub->setHeaders(array(
            'OAuth-Token' => '12345a',
            'Content-Type: application/json'
        ));
        $this->assertEquals($headers,$Stub->getHeaders());
        $options = $Stub->getOptions();
        $this->assertEquals($headers,$options[CURLOPT_HTTPHEADER]);

        $headers[] = 'Foo: Bar';
        $Stub->addHeader('Foo','Bar');
        $Stub->setHeaders();
        $this->assertEquals($headers,$Stub->getHeaders());
        $options = $Stub->getOptions();
        $this->assertEquals($headers,$options[CURLOPT_HTTPHEADER]);
        return $Stub;
    }

    /**
     * @covers ::getCurlObject
     * @covers ::getCurlResponse
     * @covers ::getCurlStatus
     * @covers ::send
     * @covers ::reset
     * @covers ::close
     * @covers ::start
     * @group abstractRequest
     */
    public function testCurl(){
        $Stub = new RequestStub($this->url);
        $CurlObject = $Stub->getCurlObject();
        $this->assertEquals(RequestStub::STATUS_INIT,$Stub->getCurlStatus());
        $Stub->close();
        $this->assertEquals(RequestStub::STATUS_CLOSED,$Stub->getCurlStatus());
        if (strpos(PHP_VERSION,'7.0') === FALSE) {
            $this->assertNotEquals('curl', get_resource_type($CurlObject));
        }
        $Stub->start();
        $this->assertEquals(RequestStub::STATUS_INIT,$Stub->getCurlStatus());
        $this->assertNotEquals($CurlObject,$Stub->getCurlObject());
        unset($Stub);
        unset($CurlObject);

        $Stub = new RequestStub($this->url);
        $CurlObject = $Stub->getCurlObject();
        $this->assertEquals(RequestStub::STATUS_INIT,$Stub->getCurlStatus());
        $Stub->send();
        $this->assertEquals(RequestStub::STATUS_SENT,$Stub->getCurlStatus());
        $this->assertEquals('boolean',gettype($Stub->getCurlResponse()));

        $Stub->reset();
        $Stub->setURL($this->url);
        $this->assertEquals(RequestStub::STATUS_INIT,$Stub->getCurlStatus());
        $this->assertNotEquals($CurlObject,$Stub->getCurlObject());
        $Stub->send();
        $this->assertEquals(RequestStub::STATUS_SENT,$Stub->getCurlStatus());
        $this->assertEquals('boolean',gettype($Stub->getCurlResponse()));
        unset($Stub);
        unset($CurlObject);

    }

    /**
     * @covers ::__destruct
     * @group abstractRequest
     */
    public function testDestructor(){
        $Stub = new RequestStub($this->url);
        $CurlObject = $Stub->getCurlObject();
        unset($Stub);
        if (strpos(PHP_VERSION,'7.0') === FALSE){
            $this->assertEquals(FALSE,is_resource($CurlObject));
        }
    }
}
