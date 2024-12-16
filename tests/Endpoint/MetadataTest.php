<?php

/**
 * ©[2024] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace Sugarcrm\REST\Tests\Endpoint;

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Psr7\Response;
use Sugarcrm\REST\Endpoint\Metadata;
use Sugarcrm\REST\Tests\Stubs\Client\Client;

/**
 * Class MetadataTest
 * @package Sugarcrm\REST\Tests\Endpoint
 * @coversDefaultClass Sugarcrm\REST\Endpoint\Metadata
 * @group MetadataTest
 */
class MetadataTest extends TestCase
{
    /**
     * @var Client
     */
    protected static $client;

    public static function setUpBeforeClass(): void
    {
        //Add Setup for static properties here
        self::$client = new Client();
    }

    public static function tearDownAfterClass(): void
    {
        //Add Tear Down for static properties here
    }

    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    /**
     * @covers ::getHash
     * @covers ::getPublic
     */
    public function testGetMetadataTypes()
    {
        self::$client->mockResponses->append(new Response(200));
        $Metadata = new Metadata();
        $Metadata->setClient(self::$client);
        // $Metadata->setAuth(new SugarOAuthController());
        $Metadata->setBaseUrl('http://localhost/rest/v11');
        $Metadata->getHash();
        $this->assertEquals([$Metadata::METADATA_TYPE_HASH], $Metadata->getUrlArgs());
        $this->assertEquals('http://localhost/rest/v11/metadata/_hash', self::$client->mockResponses->getLastRequest()->getUri()->__toString());

        self::$client->mockResponses->append(new Response(200));
        $Metadata->getPublic();
        $this->assertEquals([$Metadata::METADATA_TYPE_PUBLIC], $Metadata->getUrlArgs());
        $this->assertEquals('http://localhost/rest/v11/metadata/public', self::$client->mockResponses->getLastRequest()->getUri()->__toString());
    }
}
