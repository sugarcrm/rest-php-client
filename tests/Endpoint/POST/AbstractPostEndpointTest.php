<?php
/**
 * ©[2016] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace SugarAPI\SDK\Tests\Endpoint\POST;

use SugarAPI\SDK\Tests\Stubs\Endpoint\PostEndpointStub;

/**
 * Class AbstractEndpointTest
 * @package SugarAPI\SDK\Tests\Endpoint
 * @coversDefaultClass SugarAPI\SDK\Endpoint\Abstracts\POST\AbstractPostEndpoint
 * @group entrypoints
 */
class AbstractPostEndpointTest extends \PHPUnit_Framework_TestCase {

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
     * @return PostEndpointStub $Stub
     * @covers ::__construct
     * @group abstractEP
     */
    public function testConstructor(){
        $Stub = new PostEndpointStub($this->url);
        $this->assertInstanceOf('SugarAPI\\SDK\\Request\\POST',$Stub->getRequest());
        $this->assertEquals('http://localhost/rest/v10/$test',$Stub->getUrl());
        $this->assertEquals(array(),$Stub->getOptions());
        $this->assertEmpty($Stub->getData());
        $this->assertInstanceOf('SugarAPI\\SDK\\Response\\JSON',$Stub->getResponse());

        unset($Stub);
        $Stub = new PostEndpointStub($this->url,$this->options);
        $this->assertInstanceOf('SugarAPI\\SDK\\Request\\POST',$Stub->getRequest());
        $this->assertEquals($this->url.'foo',$Stub->getUrl());
        $this->assertEquals($this->options,$Stub->getOptions());
        $this->assertEmpty($Stub->getData());
        $this->assertInstanceOf('SugarAPI\\SDK\\Response\\JSON',$Stub->getResponse());

        unset($Delete);
        return $Stub;
    }

}
