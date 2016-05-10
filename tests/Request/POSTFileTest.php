<?php
/**
 * ©[2016] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace SugarAPI\SDK\Tests\Request;
use SugarAPI\SDK\Request\POSTFile;

/**
 * Class POSTFileTest
 * @package SugarAPI\SDK\Tests\Request
 * @coversDefaultClass SugarAPI\SDK\Request\POSTFile
 * @group requests
 */
class POSTFileTest extends \PHPUnit_Framework_TestCase {

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
     */
    public function testSetBody(){
        $Request = new POSTFile();
        $Request->setBody(array());
        $this->assertEquals(array(),$Request->getBody());
        $this->assertEquals(array(),$Request->getOptions()[CURLOPT_POSTFIELDS]);
        $Request->setBody(array('test'));
        $this->assertEquals(array('test'),$Request->getBody());
        $this->assertEquals(array('test'),$Request->getOptions()[CURLOPT_POSTFIELDS]);
        $Request->setBody('test');
        $this->assertEquals('test',$Request->getBody());
        $this->assertEquals('test',$Request->getOptions()[CURLOPT_POSTFIELDS]);
        $Request->setBody(1234);
        $this->assertEquals(1234,$Request->getBody());
        $this->assertEquals(1234,$Request->getOptions()[CURLOPT_POSTFIELDS]);
        $Request->setBody($this->body);
        $this->assertEquals($this->body,$Request->getBody());
        $this->assertEquals($this->body,$Request->getOptions()[CURLOPT_POSTFIELDS]);
    }
}
