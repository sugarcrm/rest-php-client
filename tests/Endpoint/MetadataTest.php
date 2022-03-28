<?php
/**
 * ©[2022] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace Sugarcrm\REST\Tests\Endpoint;

use Sugarcrm\REST\Auth\SugarOAuthController;
use Sugarcrm\REST\Endpoint\Metadata;
use Sugarcrm\REST\Tests\Stubs\Client\Client;

/**
 * Class MetadataTest
 * @package Sugarcrm\REST\Tests\Endpoint
 * @coversDefaultClass Sugarcrm\REST\Endpoint\Metadata
 * @group MetadataTest
 */
class MetadataTest extends \PHPUnit\Framework\TestCase {
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
     * @covers ::getHash
     * @covers ::getPublic
     */
    public function testGetMetadataTypes() {
        self::$client->mockResponses->append(new \GuzzleHttp\Psr7\Response(200));
        $Metadata = new Metadata();
        $Metadata->setClient(self::$client);
        // $Metadata->setAuth(new SugarOAuthController());
        $Metadata->setBaseUrl('http://localhost/rest/v10');
        $Metadata->getHash();
        $this->assertEquals(array($Metadata::METADATA_TYPE_HASH), $Metadata->getUrlArgs());
        $this->assertEquals('http://localhost/rest/v10/metadata/_hash', self::$client->mockResponses->getLastRequest()->getUri()->__toString());
        
        self::$client->mockResponses->append(new \GuzzleHttp\Psr7\Response(200));
        $Metadata->getPublic();
        $this->assertEquals(array($Metadata::METADATA_TYPE_PUBLIC), $Metadata->getUrlArgs());
        $this->assertEquals('http://localhost/rest/v10/metadata/public', self::$client->mockResponses->getLastRequest()->getUri()->__toString());
    }
}
