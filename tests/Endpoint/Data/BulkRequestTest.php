<?php
/**
 * ©[2022] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace Sugarcrm\REST\Tests\Endpoint\Data;

use GuzzleHttp\Psr7\Request;
use Sugarcrm\REST\Endpoint\Data\BulkRequest;
use Sugarcrm\REST\Endpoint\ModuleFilter;

/**
 * Class BulkRequestTest
 * @package Sugarcrm\REST\Tests\Endpoint\Data
 * @coversDefaultClass Sugarcrm\REST\Endpoint\Data\BulkRequest
 * @group BulkRequestTest
 */
class BulkRequestTest extends \PHPUnit\Framework\TestCase {
    protected $bulkPayload = array(
        array(
            'url' => '/v10/Accounts',
            'method' => 'POST',
            'headers' => array(
                'Host: localhost',
                'Content-Type: application/json'
            ),
            'data' => '{"foo":"bar"}'
        ),
        array(
            'url' => '/v10/Contacts/filter',
            'method' => 'POST',
            'headers' => array(
                'Host: localhost',
                'Content-Type: application/json'
            ),
            'data' => '{"offset":0,"max_num":20,"filter":[{"foo":{"$equals":"bar"}}]}'
        ),
    );

    public static function setUpBeforeClass(): void {
        //Add Setup for static properties here
    }

    public static function tearDownAfterClass(): void {
        //Add Tear Down for static properties here
    }

    public function setUp(): void {
        parent::setUp();
    }

    public function tearDown(): void {
        parent::tearDown();
    }

    /**
     * @covers ::toArray
     */
    public function testAsArray() {
        $Data = new BulkRequest();

        $Request = new Request("POST", 'http://localhost/rest/v10/Accounts', ['Content-Type' => 'application/json'], json_encode(['foo' => 'bar']));

        $Filter = new ModuleFilter(['Contacts']);
        $Filter->setBaseUrl('http://localhost/rest/v10');
        $Filter->filter()->equals('foo', 'bar');
        
        $payloadUncompiled = array(
            $Request,
            $Filter
        );
        $Data->set($payloadUncompiled);
        $this->assertEquals($payloadUncompiled, $Data->toArray(false));
        $compiled = $Data->toArray();
        $this->assertArrayHasKey(BulkRequest::BULK_REQUEST_DATA_NAME, $compiled);
        $this->assertEquals($this->bulkPayload, $compiled[BulkRequest::BULK_REQUEST_DATA_NAME]);
        $Data->reset();
        $Data->set($this->bulkPayload);
        $compiled = $Data->toArray();
        $this->assertArrayHasKey(BulkRequest::BULK_REQUEST_DATA_NAME, $compiled);
        $this->assertEquals($this->bulkPayload, $compiled[BulkRequest::BULK_REQUEST_DATA_NAME]);
        $Data->reset();
        $Data->set(array(
            BulkRequest::BULK_REQUEST_DATA_NAME => $this->bulkPayload
        ));
        $compiled = $Data->toArray();
        $this->assertArrayHasKey(BulkRequest::BULK_REQUEST_DATA_NAME, $compiled);
        $this->assertEquals($this->bulkPayload, $compiled[BulkRequest::BULK_REQUEST_DATA_NAME]);
    }

    /**
     * @covers ::extractRequest
     * @covers ::normaliseHeaders
     */
    public function testExtractRequest() {
        $Data = new BulkRequest();
        $ReflectedData = new \ReflectionClass('Sugarcrm\\REST\\Endpoint\\Data\\BulkRequest');
        $extractRequest = $ReflectedData->getMethod('extractRequest');
        $extractRequest->setAccessible(true);
        $testBodyData = json_encode(['foo' => 'bar']);
        $Request = new Request("POST", "http://localhost/rest/v10/Accounts", [], $testBodyData);
        $result = $extractRequest->invoke($Data, $Request);
        $this->assertArrayHasKey('url', $result);
        $this->assertEquals('/v10/Accounts', $result['url']);
        $this->assertArrayHasKey('method', $result);
        $this->assertEquals("POST", $result['method']);
        $this->assertArrayHasKey('headers', $result);
        $this->assertArrayHasKey('data', $result);
        $this->assertEquals($testBodyData, $result['data']);

        $Data = new BulkRequest();
        $ReflectedData = new \ReflectionClass('Sugarcrm\\REST\\Endpoint\\Data\\BulkRequest');
        $extractRequest = $ReflectedData->getMethod('extractRequest');
        $extractRequest->setAccessible(true);
        $Request = new Request("GET", "http://localhost/rest/v10/Accounts");
        $result = $extractRequest->invoke($Data, $Request);
        $this->assertArrayHasKey('url', $result);
        $this->assertEquals('/v10/Accounts', $result['url']);
        $this->assertArrayHasKey('method', $result);
        $this->assertEquals("GET", $result['method']);
        $this->assertArrayHasKey('headers', $result);
        $this->assertArrayHasKey('data', $result);
        $this->assertEquals(null, $result['data']);

        $Request = new Request("GET", "http://localhost/rest/v10/Accounts", [
                'X-Sugar-Platform' => "foobar"
            ]);
        $result = $extractRequest->invoke($Data, $Request);
        $this->assertArrayHasKey('url', $result);
        $this->assertEquals('/v10/Accounts', $result['url']);
        $this->assertArrayHasKey('method', $result);
        $this->assertEquals("GET", $result['method']);
        $this->assertArrayHasKey('headers', $result);
        $this->assertEquals([
            'Host: localhost',
            "X-Sugar-Platform: foobar"
        ],$result['headers']);
        $this->assertArrayHasKey('data', $result);
        $this->assertEquals(null, $result['data']);

        $Request = new Request("GET","");
        $this->assertFalse($extractRequest->invoke($Data, $Request));
    }
}
