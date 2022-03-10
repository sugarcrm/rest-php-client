<?php

namespace Sugarcrm\REST\Tests\Endpoint;

use Sugarcrm\REST\Endpoint\Ping;
use Sugarcrm\REST\Tests\Stubs\Client\Client;

/**
 * Class PingTest
 * @package Sugarcrm\REST\Tests\Endpoint
 * @coversDefaultClass Sugarcrm\REST\Endpoint\Ping
 * @group PingTest
 */
class PingTest extends \PHPUnit\Framework\TestCase {
    /**
     * @var Client
     */
    protected static $client;

    public static function setUpBeforeClass(): void {
        //Add Setup for static properties here
        self::$client = new Client();
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
     * @covers ::whattimeisit
     */
    public function testWhattimeisit() {
        self::$client->mockResponses->append(new \GuzzleHttp\Psr7\Response(200));
        $Ping = new Ping();
        $Ping->setClient(self::$client);
        $Ping->setBaseUrl('http://localhost/rest/v10');
        $Ping->whattimeisit();
        $this->assertEquals('http://localhost/rest/v10/ping/whattimeisit', self::$client->mockResponses->getLastRequest()->getUri());
    }
}
