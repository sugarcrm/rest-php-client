<?php
/**
 * ©[2016] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace SugarAPI\SDK\Tests\Endpoint\GET;

use SugarAPI\SDK\Tests\Stubs\Endpoint\GetEndpointStub;

/**
 * Class AbstractEndpointTest
 * @package SugarAPI\SDK\Tests\Endpoint
 * @coversDefaultClass SugarAPI\SDK\Endpoint\Abstracts\GET\AbstractGetEndpoint
 * @group entrypoints
 */
class AbstractGetEndpointTest extends \PHPUnit_Framework_TestCase {

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
     * @return GetEndpointStub $Stub
     * @covers ::__construct
     * @group abstractEP
     */
    public function testConstructor(){
        $Stub = new GetEndpointStub($this->url);
        $this->assertInstanceOf('SugarAPI\\SDK\\Request\\GET',$Stub->getRequest());
        $this->assertEquals('http://localhost/rest/v10/$test',$Stub->getUrl());
        $this->assertEquals(array(),$Stub->getOptions());
        $this->assertEmpty($Stub->getData());
        $this->assertInstanceOf('SugarAPI\\SDK\\Response\\JSON',$Stub->getResponse());

        unset($Stub);
        $Stub = new GetEndpointStub($this->url,$this->options);
        $this->assertInstanceOf('SugarAPI\\SDK\\Request\\GET',$Stub->getRequest());
        $this->assertEquals($this->url.'foo',$Stub->getUrl());
        $this->assertEquals($this->options,$Stub->getOptions());
        $this->assertEmpty($Stub->getData());
        $this->assertInstanceOf('SugarAPI\\SDK\\Response\\JSON',$Stub->getResponse());

        return $Stub;
    }

}
