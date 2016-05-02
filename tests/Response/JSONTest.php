<?php
/**
 * ©[2016] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace SugarAPI\SDK\Tests\Stubs\Response;

use SugarAPI\SDK\Response\JSON;

/**
 * Class JSONTest
 * @package SugarAPI\SDK\Tests\Stubs\Response
 * @coversDefaultClass SugarAPI\SDK\Response\JSON
 * @group responses
 */
class JSONTest extends \PHPUnit_Framework_TestCase {

    public static function setUpBeforeClass()
    {
    }

    public static function tearDownAfterClass()
    {
    }

    protected $Curl;
    protected $CurlResponse = '{"foo":"bar","bar":"foo"}';

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
     * @covers ::getJson
     * @covers ::getBody
     * @group jsonResponse
     */
    public function testJson(){
        $Stub = new JSON($this->Curl);
        $this->assertEmpty($Stub->getInfo());
        $this->assertEmpty($Stub->getBody());
        $this->assertEmpty($Stub->getError());
        $this->assertEmpty($Stub->getHeaders());
        $this->assertEmpty($Stub->getStatus());
        $this->assertEmpty($Stub->getJson());
        unset($Stub);

        $Stub = new JSON($this->Curl,$this->CurlResponse);
        $this->assertNotEmpty($Stub->getInfo());
        $this->assertEquals($this->CurlResponse,$Stub->getJson());
        $this->assertEquals(FALSE,$Stub->getError());
        $this->assertEmpty($Stub->getHeaders());
        $this->assertEmpty($Stub->getStatus());
        $this->assertEquals(json_decode($this->CurlResponse),$Stub->getBody(FALSE));
        $this->assertEquals(json_decode($this->CurlResponse,TRUE),$Stub->getBody());
    }
}
