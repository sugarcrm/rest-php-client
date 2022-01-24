<?php
/**
 * ©[2019] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace Sugarcrm\REST\Tests\Endpoint\Data;


use Sugarcrm\REST\Endpoint\Data\BulkRequest;
use Sugarcrm\REST\Endpoint\ModuleFilter;


/**
 * Class BulkRequestTest
 * @package Sugarcrm\REST\Tests\Endpoint\Data
 * @coversDefaultClass Sugarcrm\REST\Endpoint\Data\BulkRequest
 * @group BulkRequestTest
 */
class BulkRequestTest extends \PHPUnit\Framework\TestCase
{
    protected $bulkPayload = array(
        array(
            'url' => '/v10/Accounts',
            'method' => 'POST',
            'headers' => array(
                'Content-Type: application/json'
            ),
            'data' => '{"foo":"bar"}'
        ),
        array(
            'url' => '/v10/Contacts/filter',
            'method' => 'POST',
            'headers' => array(
                'Content-Type: application/json'
            ),
            'data' => '{"filter":[{"foo":{"$equals":"bar"}}],"offset":0,"max_num":20}'
        ),
    );

    public static function setUpBeforeClass(): void
    {
        //Add Setup for static properties here
    }

    public static function tearDownAfterClass(): void
    {
        //Add Tear Down for static properties here
    }

    public function setUp(): void
    {
        parent::setUp();
    }

    public function tearDown(): void
    {
        parent::tearDown();
    }

    /**
     * @covers ::asArray
     */
    public function testAsArray(){
        $Data = new BulkRequest();
        $Request = new JSON();
        $Request->setMethod("POST");
        $Request->setURL('http://localhost/rest/v10/Accounts');
        $Request->setBody(array(
            'foo' => 'bar'
        ));
        $Filter = new ModuleFilter(array('Contacts'));
        $Filter->setBaseUrl('http://localhost/rest/v10');
        $Filter->filter()->equals('foo','bar');
        $Filter->getData()->toArray();
        $payloadUncompiled = array(
            $Request,
            $Filter
        );
        $Data->update($payloadUncompiled);
        $this->assertEquals($payloadUncompiled,$Data->toArray(FALSE));
        $compiled = $Data->toArray();
        $this->assertArrayHasKey(BulkRequest::BULK_REQUEST_DATA_NAME,$compiled);
        $this->assertEquals($this->bulkPayload,$compiled[BulkRequest::BULK_REQUEST_DATA_NAME]);
        $Data->reset();
        $Data->update($this->bulkPayload);
        $compiled = $Data->toArray();
        $this->assertArrayHasKey(BulkRequest::BULK_REQUEST_DATA_NAME,$compiled);
        $this->assertEquals($this->bulkPayload,$compiled[BulkRequest::BULK_REQUEST_DATA_NAME]);
        $Data->reset();
        $Data->update(array(
            BulkRequest::BULK_REQUEST_DATA_NAME => $this->bulkPayload
        ));
        $compiled = $Data->toArray();
        $this->assertArrayHasKey(BulkRequest::BULK_REQUEST_DATA_NAME,$compiled);
        $this->assertEquals($this->bulkPayload,$compiled[BulkRequest::BULK_REQUEST_DATA_NAME]);
    }

    /**
     * @covers ::extractRequest
     */
    public function testExtractRequest(){
        $Data = new BulkRequest();
        $ReflectedData = new \ReflectionClass('Sugarcrm\\REST\\Endpoint\\Data\\BulkRequest');
        $extractRequest = $ReflectedData->getMethod('extractRequest');
        $extractRequest->setAccessible(TRUE);
        $Request = new JSON();
        $Request->setURL('http://localhost/rest/v10/Accounts');
        $Request->setBody(array('foo' => 'bar'));
        $Request->setMethod("POST");
        $result = $extractRequest->invoke($Data,$Request);
        $this->assertArrayHasKey('url',$result);
        $this->assertEquals('/v10/Accounts',$result['url']);
        $this->assertArrayHasKey('method',$result);
        $this->assertEquals("POST",$result['method']);
        $this->assertArrayHasKey('headers',$result);
        $this->assertArrayHasKey('data',$result);
        $this->assertEquals(json_encode(array('foo'=>'bar')),$result['data']);

        $Data = new BulkRequest();
        $ReflectedData = new \ReflectionClass('Sugarcrm\\REST\\Endpoint\\Data\\BulkRequest');
        $extractRequest = $ReflectedData->getMethod('extractRequest');
        $extractRequest->setAccessible(TRUE);
        $Request = new JSON();
        $Request->setURL('http://localhost/rest/v10/Accounts');
        $Request->setMethod("GET");
        $result = $extractRequest->invoke($Data,$Request);
        $this->assertArrayHasKey('url',$result);
        $this->assertEquals('/v10/Accounts',$result['url']);
        $this->assertArrayHasKey('method',$result);
        $this->assertEquals("GET",$result['method']);
        $this->assertArrayHasKey('headers',$result);
        $this->assertArrayHasKey('data',$result);
        $this->assertEquals(null,$result['data']);
    }
}
