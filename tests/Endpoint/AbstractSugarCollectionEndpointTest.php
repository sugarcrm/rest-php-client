<?php
/**
 * ©[2022] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace Sugarcrm\REST\Tests\Endpoint;

use GuzzleHttp\Psr7\Response;
use Sugarcrm\REST\Tests\Stubs\Client\Client;
use Sugarcrm\REST\Tests\Stubs\Endpoint\SugarCollectionEndpoint;

/**
 * Class AbstractSugarCollectionEndpointTest
 * @package Sugarcrm\REST\Tests\Endpoint
 * @coversDefaultClass Sugarcrm\REST\Endpoint\Abstracts\AbstractSugarCollectionEndpoint
 * @group AbstractSugarCollectionEndpointTest
 */
class AbstractSugarCollectionEndpointTest extends \PHPUnit\Framework\TestCase
{
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
     * @covers ::setOffset
     * @covers ::getOffset
     */
    public function testSetOffset()
    {
        $Endpoint = new SugarCollectionEndpoint();
        $this->assertEquals(0, $Endpoint->getOffset());
        $this->assertEquals($Endpoint, $Endpoint->setOffset(10));
        $this->assertEquals(10, $Endpoint->getOffset());
    }

    /**
     * @covers ::setLimit
     * @covers ::getLimit
     * @covers ::defaultLimit
     */
    public function testSetLimit()
    {
        $Endpoint = new SugarCollectionEndpoint();
        $this->assertEquals(50, $Endpoint->getLimit());
        $Endpoint = new SugarCollectionEndpoint([], [SugarCollectionEndpoint::PROPERTY_SUGAR_DEFAULT_LIMIT => 100]);
        $this->assertEquals(100, $Endpoint->getLimit());
        $this->assertEquals($Endpoint, $Endpoint->setLimit(10));
        $this->assertEquals(10, $Endpoint->getLimit());
    }

    /**
     * @covers ::configurePayload
     */
    public function testConfigurePayload()
    {
        $Endpoint = new SugarCollectionEndpoint();
        $Reflection = new \ReflectionClass('Sugarcrm\REST\Tests\Stubs\Endpoint\SugarCollectionEndpoint');
        $configurePayload = $Reflection->getMethod('configurePayload');
        $configurePayload->setAccessible(true);
        $this->assertEquals(array(
            'offset' => 0,
            'max_num' => 50
        ), $configurePayload->invoke($Endpoint)->toArray());
    }

    /**
     * @covers ::nextPage
     * @covers ::previousPage
     * @covers ::parseResponse
     * @covers ::reset
     * @return void
     */
    public function testPagination()
    {
        $Client = new Client();
        $Endpoint = new SugarCollectionEndpoint();
        $Endpoint->setClient($Client);

        $Reflect = new \ReflectionClass($Endpoint);
        $nextOffset = $Reflect->getProperty('_next_offset');
        $nextOffset->setAccessible(true);

        $Client->mockResponses->append(new Response(200, [], \json_encode(['next_offset' => 50])));
        $this->assertEquals($Endpoint, $Endpoint->fetch());
        $request = $Client->mockResponses->getLastRequest();
        $this->assertTrue(strpos($request->getUri()->getQuery(), "max_num=50") !== false);
        $this->assertTrue(strpos($request->getUri()->getQuery(), "offset=0") !== false);
        $this->assertEquals(50, $Endpoint->getLimit());
        $this->assertEquals(0, $Endpoint->getOffset());
        $this->assertEquals(50, $nextOffset->getValue($Endpoint));

        $Client->mockResponses->append(new Response(200, [], \json_encode(['next_offset' => 100])));
        $this->assertEquals($Endpoint, $Endpoint->nextPage());
        $request = $Client->mockResponses->getLastRequest();
        $this->assertTrue(strpos($request->getUri()->getQuery(), "max_num=50") !== false);
        $this->assertTrue(strpos($request->getUri()->getQuery(), "offset=50") !== false);
        $this->assertEquals(50, $Endpoint->getLimit());
        $this->assertEquals(50, $Endpoint->getOffset());
        $this->assertEquals(100, $nextOffset->getValue($Endpoint));

        $Client->mockResponses->append(new Response(200, [], \json_encode(['next_offset' => 150])));
        $this->assertEquals($Endpoint, $Endpoint->nextPage());
        $request = $Client->mockResponses->getLastRequest();
        $this->assertTrue(strpos($request->getUri()->getQuery(), "max_num=50") !== false);
        $this->assertTrue(strpos($request->getUri()->getQuery(), "offset=100") !== false);
        $this->assertEquals(50, $Endpoint->getLimit());
        $this->assertEquals(100, $Endpoint->getOffset());
        $this->assertEquals(150, $nextOffset->getValue($Endpoint));

        $Client->mockResponses->append(new Response(200));
        $this->assertEquals($Endpoint, $Endpoint->previousPage());
        $request = $Client->mockResponses->getLastRequest();
        $this->assertTrue(strpos($request->getUri()->getQuery(), "max_num=50") !== false);
        $this->assertTrue(strpos($request->getUri()->getQuery(), "offset=50") !== false);
        $this->assertEquals(50, $Endpoint->getLimit());
        $this->assertEquals(50, $Endpoint->getOffset());
        //not in response
        $this->assertEquals(150, $nextOffset->getValue($Endpoint));
        $Endpoint->reset();
        $this->assertEquals(50, $Endpoint->getLimit());
        $this->assertEquals(0, $Endpoint->getOffset());
        $this->assertEquals(0, $nextOffset->getValue($Endpoint));
    }
}
