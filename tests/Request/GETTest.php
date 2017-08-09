<?php
/**
 * ©[2016] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace SugarAPI\SDK\Tests\Request;
use SugarAPI\SDK\Request\GET;

/**
 * Class GETTest
 * @package SugarAPI\SDK\Tests\Request
 * @coversDefaultClass SugarAPI\SDK\Request\GET
 * @group requests
 */
class GETTest extends \PHPUnit_Framework_TestCase {

    public static function setUpBeforeClass()
    {
    }

    public static function tearDownAfterClass()
    {
    }
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
     * @covers ::setBody
     * @group requests
     * @return GET
     */
    public function testSetBody(){
        $Request = new GET();
        $Request->setBody(array());
        $this->assertEquals(http_build_query(array()),$Request->getBody());
        $Request->setBody("test=foo&bar=test");
        $this->assertEquals("test=foo&bar=test",$Request->getBody());
        $Request->setBody(array('test'));
        $this->assertEquals(http_build_query(array('test')),$Request->getBody());
        $Request->setBody($this->body);
        $this->assertEquals(http_build_query($this->body),$Request->getBody());
        return $Request;
    }

    /**
     * @param GET $Request
     * @depends testSetBody
     * @covers ::send
     * @group requests
     */
    public function testSend($Request){
        $Request->setURL('http://localhost');
        $Request->send();
        $this->assertEquals('http://localhost?foo=bar',$Request->getURL());
        unset($Request);

        $Request = new GET('http://localhost?foo=bar');
        $Request->send();
        $this->assertEquals('http://localhost?foo=bar',$Request->getURL());
        unset($Request);

        $Request = new GET('http://localhost?bar=foo');
        $Request->setBody($this->body);
        $Request->send();
        $this->assertEquals('http://localhost?bar=foo&foo=bar',$Request->getURL());
    }
}
