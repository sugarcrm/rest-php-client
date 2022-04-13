<?php
/**
 * ©[2022] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace Sugarcrm\REST\Tests\Endpoint;

use GuzzleHttp\Psr7\MultipartStream;
use Sugarcrm\REST\Endpoint\Note;
use Sugarcrm\REST\Endpoint\Ping;
use Sugarcrm\REST\Tests\Stubs\Client\Client;

/**
 * Class PingTest
 * @package Sugarcrm\REST\Tests\Endpoint
 * @coversDefaultClass Sugarcrm\REST\Endpoint\Note
 * @group PingTest
 */
class NoteTest extends \PHPUnit\Framework\TestCase {
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
        self::$client->container = [];
        self::$client->mockResponses->reset();
        parent::tearDown();
    }

    /**
     * @covers ::resetAttachments
     * @covers ::deleteAttachments
     * @covers ::clear
     * @covers ::hasAttachmentsChanges
     * @return void
     */
    public function testAttachmentsProps() {
        $Note = new Note();
        $Note->setClient(self::$client);
        $Reflection = new \ReflectionClass($Note);
        $attachments = $Reflection->getProperty('_attachments');
        $attachments->setAccessible(true);
        $this->assertEquals([
            'add' => [],
            'delete' => [],
            'create' => []
        ],$attachments->getValue($Note));

        $attachments->setValue($Note,[
            'add' => ['12345'],
            'delete' => ['67890'],
            'create' => [[
                'id' => '123456',
                'name' => 'foobar.txt'
            ]]
        ]);
        $hasAttachmentsChanges = $Reflection->getMethod("hasAttachmentsChanges");
        $hasAttachmentsChanges->setAccessible(true);
        $this->assertTrue($hasAttachmentsChanges->invoke($Note));
        $this->assertEquals($Note,$Note->clear());
        $this->assertFalse($hasAttachmentsChanges->invoke($Note));
        $this->assertEquals($Note,$Note->deleteAttachments('12345'));
        $this->assertTrue($hasAttachmentsChanges->invoke($Note));
        $this->assertEquals([
            'add' => [],
            'delete' => ['12345'],
            'create' => []
        ],$attachments->getValue($Note));
        $this->assertEquals($Note,$Note->deleteAttachments(['12345','67890']));
        $this->assertTrue($hasAttachmentsChanges->invoke($Note));
        $this->assertEquals([
            'add' => [],
            'delete' => ['12345','12345','67890'],
            'create' => []
        ],$attachments->getValue($Note));
        $this->assertEquals($Note,$Note->resetAttachments());
        $this->assertFalse($hasAttachmentsChanges->invoke($Note));
    }


    /**
     * @covers ::parseFiles
     */
    public function testParseFiles()
    {
        $Note = new Note();
        $Note->setClient(self::$client);
        $Reflection = new \ReflectionClass($Note);
        $parseFiles = $Reflection->getMethod('parseFiles');
        $parseFiles->setAccessible(true);
        $this->assertEquals([[
            'name' => basename(__FILE__),
            'path' => __FILE__,
        ]],$parseFiles->invoke($Note,[__FILE__]));
        $this->assertEquals([[
            'name' => 'test.php',
            'path' => __FILE__,
        ]],$parseFiles->invoke($Note,[['path' => __FILE__,'name' => 'test.php']]));

        $file = new \stdClass();
        $file->path = __FILE__;

        $this->assertEquals([[
            'name' => basename(__FILE__),
            'path' => __FILE__,
        ]],$parseFiles->invoke($Note,[['path' => 'foobar'],$file]));
    }

    /**
     * @covers ::multiAttach
     * @covers ::parseResponse
     * @covers ::configurePayload
     * @covers ::configureUrl
     */
    public function testMultiattach() {
        self::$client->mockResponses->append(new \GuzzleHttp\Psr7\Response(200,[],json_encode([
            'record' => [
                'id' => '12345',
                'name' => 'foobar.txt',
            ],
        ])));
        self::$client->mockResponses->append(new \GuzzleHttp\Psr7\Response(200,[],json_encode([
            'id' => '567890',
            'name' => 'Test note'
        ])));
        $Note = new Note();
        $Note->setClient(self::$client);
        $Note['name'] = 'Test note';
        $this->assertEquals($Note,$Note->multiAttach([__FILE__]));
        $upload = self::$client->container[0];
        $this->assertEquals('/rest/v11/Notes/temp/file/filename',$upload['request']->getUri()->getPath());
        $this->assertEquals('POST',$upload['request']->getMethod());
        $this->assertInstanceOf(MultipartStream::class,$upload['request']->getBody());
        $request = self::$client->mockResponses->getLastRequest();
        $this->assertEquals('/rest/v11/Notes',$request->getUri()->getPath());
        $this->assertEquals('POST',$request->getMethod());
        $this->assertEquals(json_encode([
            'name' => 'Test note',
            'attachments' => [
                'add' => [],
                'delete' => [],
                'create' => [
                    [
                        'id' => '12345',
                        'name' => 'foobar.txt',
                        'filename_guid' => '12345'
                    ]
                ]
            ]
        ]),$request->getBody()->getContents());
    }
}
