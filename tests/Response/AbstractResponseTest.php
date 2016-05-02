<?php
/**
 * ©[2016] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace SugarAPI\SDK\Tests\Stubs\Response;

/**
 * Class AbstractResponseTest
 * @package SugarAPI\SDK\Tests\Response\AbstractResponseTest
 * @coversDefaultClass SugarAPI\SDK\Response\Abstracts\AbstractResponse
 * @group responses
 */
class AbstractResponseTest extends \PHPUnit_Framework_TestCase {

    public static function setUpBeforeClass()
    {
    }

    public static function tearDownAfterClass()
    {
    }

    protected $Curl;
    protected $CurlResponse = 'Test is a test';

    public function setUp()
    {
        $this->Curl = curl_init();
        parent::setUp();
    }

    public function tearDown()
    {
        curl_close($this->Curl);
        unset($this->Curl);
        parent::tearDown();
    }

    /**
     * @covers ::__construct
     * @covers ::setCurlResponse
     * @covers ::extractInfo
     * @covers ::extractResponse
     * @covers ::getInfo
     * @covers ::getBody
     * @covers ::getError
     * @covers ::getHeaders
     * @covers ::getStatus
     * @group abstractResponse
     */
    public function testConstructor(){
        $Stub = new ResponseStub($this->Curl);
        $this->assertEmpty($Stub->getInfo());
        $this->assertEmpty($Stub->getBody());
        $this->assertEmpty($Stub->getError());
        $this->assertEmpty($Stub->getHeaders());
        $this->assertEmpty($Stub->getStatus());
        unset($Stub);

        $Stub = new ResponseStub($this->Curl,$this->CurlResponse);
        $this->assertNotEmpty($Stub->getInfo());
        $this->assertEquals($this->CurlResponse,$Stub->getBody());
        $this->assertEquals(FALSE,$Stub->getError());
        $this->assertEmpty($Stub->getHeaders());
        $this->assertEmpty($Stub->getStatus());
    }

    /**
     * @covers ::extractInfo
     * @covers ::getError
     * @group abstractResponse
     */
    public function testCurlErrors(){
        curl_setopt($this->Curl,CURLOPT_URL, 'test.foo.bar');
        curl_exec($this->Curl);
        $Stub = new ResponseStub($this->Curl,$this->CurlResponse);
        $this->assertNotEmpty($Stub->getInfo());
        $this->assertNotEmpty($Stub->getError());
        $this->assertEmpty($Stub->getBody());
        $this->assertEmpty($Stub->getHeaders());
        $this->assertEmpty($Stub->getStatus());
    }
}
