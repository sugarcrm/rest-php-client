<?php

/**
 * ©[2022] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace Sugarcrm\REST\Tests\Endpoint;

use GuzzleHttp\Psr7\MultipartStream;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Utils;
use PhpParser\Node\Expr\AssignOp\Mod;
use Sugarcrm\REST\Endpoint\Module;
use Sugarcrm\REST\Tests\Stubs\Auth\SugarOAuthStub;
use Sugarcrm\REST\Tests\Stubs\Client\Client;

/**
 * Class AbstractSugarBeanEndpointTest
 * @package Sugarcrm\REST\Tests\Endpoint
 * @coversDefaultClass Sugarcrm\REST\Endpoint\Abstracts\AbstractSugarBeanEndpoint
 * @group AbstractSugarBeanEndpointTest
 */
class AbstractSugarBeanEndpointTest extends \PHPUnit\Framework\TestCase
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

    public function setUp(): void
    {
        parent::setUp();
    }

    public function tearDown(): void
    {
        parent::tearDown();
    }

    /**
     * @covers ::compileRequest
     */
    public function testCompileRequest()
    {
        $Bean = new Module();
        $Bean->setBaseUrl('http://localhost/rest/v11/');
        $Bean->setUrlArgs(['Foo', 'bar']);
        $Request = $Bean->compileRequest();
        $this->assertEquals("GET", $Request->getMethod());
        $this->assertEquals('http://localhost/rest/v11/Foo/bar', $Request->getUri()->__toString());
        $this->assertEmpty($Request->getBody()->getContents());
    }

    /**
     * @covers ::setUrlArgs
     * @covers ::getModule
     * @covers ::configureModuleUrlArg
     */
    public function testSetUrlArgs()
    {
        $Bean = new Module();

        $this->assertEquals($Bean, $Bean->setUrlArgs(array(
            'Test'
        )));
        $this->assertEquals(array(
            'module' => 'Test'
        ), $Bean->getUrlArgs());
        $this->assertEquals('Test', $Bean->getModule());
        $this->assertEquals($Bean, $Bean->setUrlArgs(array(
            'Test',
            '123-abc'
        )));
        $this->assertEquals(array(
            'module' => 'Test',
        ), $Bean->getUrlArgs());
        $this->assertEquals('Test', $Bean->getModule());
        $this->assertEquals('123-abc', $Bean->get('id'));
        $this->assertEquals($Bean, $Bean->setUrlArgs(array(
            'Test',
            '123-abc',
            'foo'
        )));
        $this->assertEquals(array(
            2 => 'foo',
            'module' => 'Test',
        ), $Bean->getUrlArgs());
        $this->assertEquals('Test', $Bean->getModule());
        $this->assertEquals('123-abc', $Bean->get('id'));
    }

    /**
     * @covers ::setModule
     * @covers ::configureModuleUrlArg
     */
    public function testSetModule()
    {
        $Bean = new Module();
        $this->assertEquals($Bean, $Bean->setModule('Test'));
        $this->assertEquals('Test', $Bean->getModule());

        $Reflection = new \ReflectionClass($Bean);
        $configureModuleArg = $Reflection->getMethod('configureModuleUrlArg');
        $configureModuleArg->setAccessible(true);

        $args = [
            0 => 'foobar'
        ];
        $args = $configureModuleArg->invoke($Bean, $args);
        $this->assertFalse(isset($args[0]));
        $this->assertEquals('foobar', $Bean->getModule());
        $this->assertEquals('foobar', $args['module']);
        $args = $configureModuleArg->invoke($Bean, []);
        $this->assertFalse(isset($args[0]));
        $this->assertEquals('foobar', $Bean->getModule());
        $this->assertEquals('foobar', $args['module']);
    }

    /**
     * @covers ::setView
     * @covers ::getView
     * @covers ::setFields
     * @covers ::getFields
     * @covers ::addField
     * @covers ::configureFieldsDataProps
     * @covers ::reset
     */
    public function testFieldsProperties()
    {
        $Bean = new Module();
        $this->assertEquals($Bean, $Bean->setView('record'));
        $this->assertEquals('record', $Bean->getView());
        $this->assertEquals($Bean, $Bean->setFields(['id','deleted','date_modified']));
        $this->assertEquals(['id','deleted','date_modified'], $Bean->getFields());
        $this->assertEquals($Bean, $Bean->addField('foobar'));
        $this->assertEquals(['id','deleted','date_modified','foobar'], $Bean->getFields());
        //check deduping
        $this->assertEquals($Bean, $Bean->addField('foobar'));
        $this->assertEquals(['id','deleted','date_modified','foobar'], $Bean->getFields());

        $Reflection = new \ReflectionClass($Bean);
        $configureFieldsDataProps = $Reflection->getMethod('configureFieldsDataProps');
        $configureFieldsDataProps->setAccessible(true);
        $this->assertEquals([
            'fields' => "id,deleted,date_modified,foobar",
            'view' => 'record'
        ], $configureFieldsDataProps->invoke($Bean, []));
        $Bean->reset();
        $this->assertEmpty($Bean->getView());
        $this->assertEmpty($Bean->getFields());
    }

    /**
     * @covers ::relate
     */
    public function testRelate()
    {
        self::$client->mockResponses->append(new Response(200));
        $Bean = new Module();
        $Bean->setClient(self::$client);
        $Bean->setBaseUrl('http://localhost/rest/v11/');
        $Bean->setUrlArgs(['Foo', 'bar']);
        $this->assertEquals($Bean, $Bean->relate('baz', 'foz'));
        $request = self::$client->mockResponses->getLastRequest();
        $this->assertEquals('/rest/v11/Foo/bar/link/baz/foz', $request->getUri()->getPath());
        $this->assertEquals('POST', $request->getMethod());
    }

    /**
     * @covers ::files
     */
    public function testFiles()
    {
        $Bean = new Module();
        self::$client->mockResponses->append(new Response(200));
        $Bean->setClient(self::$client);
        $Bean->setBaseUrl('http://localhost/rest/v11/');
        $Bean->setUrlArgs(['Foo', 'bar']);
        $this->assertEquals($Bean, $Bean->files());
        $request = self::$client->mockResponses->getLastRequest();
        $this->assertEquals('/rest/v11/Foo/bar/file', $request->getUri()->getPath());
        $this->assertEquals('GET', $request->getMethod());
    }

    /**
     * @covers ::massRelate
     */
    public function testMassRelated()
    {
        $Bean = new Module();
        self::$client->container = [];
        self::$client->mockResponses->append(new Response(200));
        $Bean->setClient(self::$client);
        $Bean->setBaseUrl('http://localhost/rest/v11/');
        $Bean->setUrlArgs(['Foo', 'bar']);

        $this->assertEquals($Bean, $Bean->massRelate('baz', ['1234', '5678']));
        $request = current(self::$client->container)['request'];
        $this->assertEquals('http://localhost/rest/v11/Foo/bar/link', $request->getUri()->__toString());
        $this->assertEquals('POST', $request->getMethod());

        $this->assertEquals('{"link_name":"baz","ids":["1234","5678"]}', $request->getBody()->getContents());
    }

    /**
     * @covers ::follow
     */
    public function testFollow()
    {
        $Bean = new Module();
        self::$client->container = [];
        self::$client->mockResponses->append(new Response(200));
        $Bean->setClient(self::$client);
        $Bean->setBaseUrl('http://localhost/rest/v11/');
        $Bean->setUrlArgs(['Foo', 'bar']);
        $Bean->follow();
        $request = current(self::$client->container)['request'];
        $this->assertEquals('http://localhost/rest/v11/Foo/bar/subscribe', $request->getUri()->__toString());
        $this->assertEquals('POST', $request->getMethod());
    }

    /**
     * @covers ::unfollow
     */
    public function testUnfollow()
    {
        $Bean = new Module();
        self::$client->container = [];
        self::$client->mockResponses->append(new Response(200));
        $Bean->setClient(self::$client);
        $Bean->setBaseUrl('http://localhost/rest/v11/');
        $Bean->setUrlArgs(['Foo', 'bar']);
        $Bean->unfollow();
        $request = current(self::$client->container)['request'];
        $this->assertEquals('http://localhost/rest/v11/Foo/bar/unsubscribe', $request->getUri()->__toString());
        $this->assertEquals('DELETE', $request->getMethod());
    }

    /**
     * @covers ::getRelated
     */
    public function testGetRelated()
    {
        $Bean = new Module();
        self::$client->mockResponses->append(new Response(200));
        $Bean->setClient(self::$client);
        $Bean->setBaseUrl('http://localhost/rest/v11/');
        $Bean->setUrlArgs(['Foo', 'bar']);
        $this->assertEquals($Bean, $Bean->getRelated('test'));
        $this->assertEquals('http://localhost/rest/v11/Foo/bar/link/test', self::$client->mockResponses->getLastRequest()->getUri()->__toString());
        $this->assertEquals('GET', self::$client->mockResponses->getLastRequest()->getMethod());

        self::$client->mockResponses->append(new Response(200));
        $this->assertEquals($Bean, $Bean->getRelated('test', true));
        $this->assertEquals('http://localhost/rest/v11/Foo/bar/link/test/count', self::$client->mockResponses->getLastRequest()->getUri()->__toString());
        $this->assertEquals('GET', self::$client->mockResponses->getLastRequest()->getMethod());
    }

    /**
     * @covers ::auditLog
     */
    public function testAuditLog()
    {
        $Bean = new Module();

        $auditResponse = [
            'records' => [
                [
                    'id' => '12345',
                    'parent_id' => 'some_parent_id',
               ]
            ],
        ];

        self::$client->mockResponses->append(new Response(200, [], json_encode($auditResponse)));

        $Bean->setClient(self::$client);
        self::$client->setVersion("10");
        $Bean->setUrlArgs(['Foo', 'bar']);
        $Audit = $Bean->auditLog(100);
        $this->assertInstanceOf('Sugarcrm\\REST\\Endpoint\\ModuleAudit', $Audit);
        $this->assertEquals($auditResponse['records'], array_values($Audit->toArray()));
        $this->assertEquals('/rest/v11_11/Foo/bar/audit', self::$client->mockResponses->getLastRequest()->getUri()->getPath());
        parse_str(self::$client->mockResponses->getLastRequest()->getUri()->getQuery(), $query);
        $this->assertEquals(100, $query['max_num']);
        $this->assertEquals('GET', self::$client->mockResponses->getLastRequest()->getMethod());
    }

    /**
     * @covers ::filterRelated
     * @covers Sugarcrm\REST\Endpoint\Data\FilterData::execute
     */
    public function testFilterRelated()
    {
        $Bean = new Module();
        self::$client->mockResponses->append(new Response(200));
        $Bean->setClient(self::$client);
        $Bean->setBaseUrl('http://localhost/rest/v11/');
        $Bean->setUrlArgs(['Foo', 'bar']);
        $Filter = $Bean->filterRelated('test');
        $this->assertInstanceOf('Sugarcrm\\REST\\Endpoint\\Data\\FilterData', $Filter);
        $this->assertEquals($Bean, $Filter->execute());
        $this->assertEquals('http://localhost/rest/v11/Foo/bar/link/test', self::$client->mockResponses->getLastRequest()->getUri()->__toString());
        $this->assertEquals('GET', self::$client->mockResponses->getLastRequest()->getMethod());

        self::$client->mockResponses->append(new Response(200));
        $Filter = $Bean->filterRelated('test', true);
        $Filter->equals('name', 'foobar');
        $this->assertInstanceOf('Sugarcrm\\REST\\Endpoint\\Data\\FilterData', $Filter);
        $this->assertEquals($Bean, $Filter->execute());
        $this->assertEquals('/rest/v11/Foo/bar/link/test/count', self::$client->mockResponses->getLastRequest()->getUri()->getPath());
        $this->assertEquals('GET', self::$client->mockResponses->getLastRequest()->getMethod());
        parse_str(self::$client->mockResponses->getLastRequest()->getUri()->getQuery(), $query);
        $this->assertArrayHasKey('filter', $query);
    }

    /**
     * @covers ::configureURL
     */
    public function testConfigureURL()
    {
        $options = ['Foo', 'bar'];
        $Bean = new Module();
        $Bean->setBaseUrl('http://localhost/rest/v11/');
        $Bean->setUrlArgs($options);
        $ReflectedBean = new \ReflectionClass('Sugarcrm\REST\Endpoint\Module');
        $configureUrl = $ReflectedBean->getMethod('configureURL');
        $configureUrl->setAccessible(true);

        $Bean->setCurrentAction(Module::MODEL_ACTION_RETRIEVE);
        $this->assertEquals('Foo/bar', $configureUrl->invoke($Bean, $Bean->getUrlArgs()));
        $Bean->setCurrentAction(Module::MODEL_ACTION_UPDATE);
        $this->assertEquals('Foo/bar', $configureUrl->invoke($Bean, $Bean->getUrlArgs()));
        $Bean->setCurrentAction(Module::MODEL_ACTION_DELETE);
        $this->assertEquals('Foo/bar', $configureUrl->invoke($Bean, $Bean->getUrlArgs()));
        $Bean->setCurrentAction(Module::MODEL_ACTION_CREATE);
        $this->assertEquals('Foo', $configureUrl->invoke($Bean, $Bean->getUrlArgs()));
        $Bean->setCurrentAction(Module::BEAN_ACTION_UNFOLLOW);
        $this->assertEquals('Foo/bar/unsubscribe', $configureUrl->invoke($Bean, $Bean->getUrlArgs()));
        $Bean->setCurrentAction(Module::BEAN_ACTION_UNFAVORITE);
        $this->assertEquals('Foo/bar/unfavorite', $configureUrl->invoke($Bean, $Bean->getUrlArgs()));
        $Bean->setCurrentAction(Module::BEAN_ACTION_FAVORITE);
        $this->assertEquals('Foo/bar/favorite', $configureUrl->invoke($Bean, $Bean->getUrlArgs()));
        $Bean->setCurrentAction(Module::BEAN_ACTION_FOLLOW);
        $this->assertEquals('Foo/bar/subscribe', $configureUrl->invoke($Bean, $Bean->getUrlArgs()));
        $Bean->setCurrentAction(Module::BEAN_ACTION_FILE);
        $this->assertEquals('Foo/bar/file', $configureUrl->invoke($Bean, $Bean->getUrlArgs()));
        $Bean->setCurrentAction(Module::BEAN_ACTION_AUDIT);
        $this->assertEquals('Foo/bar/audit', $configureUrl->invoke($Bean, $Bean->getUrlArgs()));

        //More Options Needed
        $options[] = 'baz';
        $Bean->setUrlArgs($options);
        $Bean->setCurrentAction(Module::BEAN_ACTION_CREATE_RELATED);
        $this->assertEquals('Foo/bar/link/baz', $configureUrl->invoke($Bean, $Bean->getUrlArgs()));
        $options[] = 'foz';
        $Bean->setUrlArgs($options);
        $Bean->setCurrentAction(Module::BEAN_ACTION_UNLINK);
        $this->assertEquals('Foo/bar/link/baz/foz', $configureUrl->invoke($Bean, $Bean->getUrlArgs()));
        $Bean->setCurrentAction(Module::BEAN_ACTION_RELATE);
        $this->assertEquals('Foo/bar/link/baz/foz', $configureUrl->invoke($Bean, $Bean->getUrlArgs()));
        $options = array('Foo', 'bar', 'uploadFile');
        $Bean->setUrlArgs($options);
        $Bean->setCurrentAction(Module::BEAN_ACTION_ATTACH_FILE);
        $this->assertEquals('Foo/bar/file/uploadFile', $configureUrl->invoke($Bean, $Bean->getUrlArgs()));
        $Bean->setCurrentAction(Module::BEAN_ACTION_DOWNLOAD_FILE);
        $this->assertEquals('Foo/bar/file/uploadFile', $configureUrl->invoke($Bean, $Bean->getUrlArgs()));
        $options = array(
            'Foo', 'bar',
            'action' => 'test'
        );
        $Bean->setCurrentAction(Module::MODEL_ACTION_RETRIEVE);
        unset($Bean[$Bean->modelIdKey()]);
        $this->assertEquals('Foo/bar', $configureUrl->invoke($Bean, $options));
    }

    /**
     * @covers ::configureAction
     */
    public function testConfigureAction()
    {
        $Bean = new Module();
        $Bean->setBaseUrl('http://localhost/rest/v11/');
        $ReflectedBean = new \ReflectionClass('Sugarcrm\REST\Endpoint\Module');
        $configureAction = $ReflectedBean->getMethod('configureAction');
        $configureAction->setAccessible(true);
        $Bean->setUrlArgs(array('Test', '1234'));
        $configureAction->invoke($Bean, Module::BEAN_ACTION_RELATE, array('foo', 'bar'));
        $this->assertEquals('Test', $Bean->getModule());
        $this->assertEquals(array(
            'module' => 'Test',
            'actionArg1' => 'foo',
            'actionArg2' => 'bar'
        ), $Bean->getUrlArgs());

        $Bean->setUrlArgs(array('Test', '1234'));
        $configureAction->invoke($Bean, Module::BEAN_ACTION_ATTACH_FILE, array('fileField'));
        $this->assertEquals('Test', $Bean->getModule());
        $this->assertEquals(array(
            'module' => 'Test',
            'actionArg1' => 'fileField',
        ), $Bean->getUrlArgs());

        $Bean->setUrlArgs(array('Test', '1234'));
        $configureAction->invoke($Bean, Module::BEAN_ACTION_DOWNLOAD_FILE, array('fileField'));
        $this->assertEquals('Test', $Bean->getModule());
        $this->assertEquals(array(
            'module' => 'Test',
            'actionArg1' => 'fileField',
        ), $Bean->getUrlArgs());

        $Bean->setUrlArgs(array('Test', '1234'));
        $configureAction->invoke($Bean, Module::BEAN_ACTION_UNLINK, array('foo', 'bar'));
        $this->assertEquals('Test', $Bean->getModule());
        $this->assertEquals(array(
            'module' => 'Test',
            'actionArg1' => 'foo',
            'actionArg2' => 'bar'
        ), $Bean->getUrlArgs());

        $Bean->setUrlArgs(array('Test', '1234'));
        $configureAction->invoke($Bean, Module::BEAN_ACTION_CREATE_RELATED, array('foo', 'bar', 'baz'));
        $this->assertEquals('Test', $Bean->getModule());
        $this->assertEquals(array(
            'module' => 'Test',
            'actionArg1' => 'foo',
            'actionArg2' => 'bar',
            'actionArg3' => 'baz'
        ), $Bean->getUrlArgs());

        $Bean->setUrlArgs(array('Test', '1234'));
        $configureAction->invoke($Bean, Module::MODEL_ACTION_CREATE, array('foo', 'bar', 'baz'));
        $this->assertEquals('Test', $Bean->getModule());
        $this->assertEquals(array(
            'module' => 'Test',
        ), $Bean->getUrlArgs());
    }

    /**
     * @covers ::configureRequest
     * @covers ::setFields
     * @covers ::getFields
     * @covers ::setView
     * @covers ::getView
     * @covers ::configureFieldsDataProps
     * @return void
     */
    public function testRetrieve()
    {
        $Bean = new Module();
        $Bean->setClient(self::$client);

        self::$client->mockResponses->append(new Response(200, [], json_encode(array('foo' => 'bar','baz' => 'foz'))));
        self::$client->mockResponses->append(new Response(200, [], json_encode(array('foo' => 'bar','baz' => 'foz'))));
        $Bean->setModule('Accounts');
        $this->assertEquals($Bean, $Bean->retrieve('12345'));
        $request = self::$client->mockResponses->getLastRequest();
        $this->assertEquals('/rest/v11/Accounts/12345', $request->getUri()->getPath());
        $this->assertEquals($Bean, $Bean->setFields(['foo','baz']));
        $this->assertEquals(['foo','baz'], $Bean->getFields());
        $this->assertEquals($Bean, $Bean->setView('record'));
        $this->assertEquals('record', $Bean->getView());
        $this->assertEquals($Bean, $Bean->retrieve('12345'));
        $request = self::$client->mockResponses->getLastRequest();
        $this->assertEquals('/rest/v11/Accounts/12345', $request->getUri()->getPath());
        $this->assertEquals(\http_build_query([
            'fields' => implode(",", ["foo","baz"]),
            'view' => 'record'
        ]), $request->getUri()->getQuery());
    }

    /**
     * @covers ::parseResponse
     * @covers ::configurePayload
     * @return void
     * @throws \MRussell\REST\Exception\Endpoint\InvalidRequest
     */
    public function testBeanSave()
    {
        self::$client->mockResponses->append(new Response(200, [], json_encode(array('id'=> '12345','foo' => 'bar','baz' => 'foz'))));
        self::$client->mockResponses->append(new Response(200, [], json_encode(array('id'=> '12345','foo' => 'bar','baz' => 'foz'))));
        $Bean = new Module();
        $Bean->setClient(self::$client);
        $Bean->setModule('Accounts');
        $Bean->set(['foo' => 'bar','baz' => 'foz']);
        $this->assertEquals($Bean, $Bean->save());
        $this->assertEquals('create', $Bean->getCurrentAction());
        $this->assertEquals('12345', $Bean['id']);
        $request = self::$client->mockResponses->getLastRequest();
        $this->assertEquals('/rest/v11/Accounts', $request->getUri()->getPath());
        $this->assertEquals('POST', $request->getMethod());
        $this->assertEquals(json_encode(['foo' => 'bar','baz' => 'foz']), $request->getBody()->getContents());
        $Bean->getData()['test'] = 'should not pass to api';
        $this->assertEquals($Bean, $Bean->save());
        $this->assertEquals('update', $Bean->getCurrentAction());
        $request = self::$client->mockResponses->getLastRequest();
        $this->assertEquals('/rest/v11/Accounts/12345', $request->getUri()->getPath());
        $this->assertEquals('PUT', $request->getMethod());
        $this->assertEquals(json_encode(['foo' => 'bar','baz' => 'foz','id' => '12345']), $request->getBody()->getContents());
    }

    /**
     * Tests handling the custom actions response parsing
     * @covers ::parseResponse
     * @covers ::reset
     */
    public function testUpdateModel()
    {
        $Bean = new Module();
        $Bean->setClient(self::$client);
        $response = new Response(200, [], json_encode(array('foo' => 'bar','baz' => 'foz')));
        self::$client->mockResponses->append();

        $Bean->setCurrentAction(Module::BEAN_ACTION_FAVORITE);
        $Bean->setResponse($response);
        $this->assertEquals(array(
             'foo' => 'bar',
             'baz' => 'foz'
         ), $Bean->toArray());
        $response = new Response(200, [], json_encode(array('foo' => 'foz','baz' => 'bar','favorite' => 0)));
        $Bean->setCurrentAction(Module::BEAN_ACTION_UNFAVORITE);
        $Bean->setResponse($response);
        $this->assertEquals(array(
             'foo' => 'foz',
             'baz' => 'bar',
             'favorite' => 0
         ), $Bean->toArray());

        $response = new Response(200, [], json_encode(array('foo' => 'bar','baz' => 'foz')));
        $Bean->setCurrentAction(Module::BEAN_ACTION_AUDIT);
        $Bean->setResponse($response);
        $this->assertEquals(array(
             'foo' => 'foz',
             'baz' => 'bar',
             'favorite' => 0
         ), $Bean->toArray());

        $Bean->reset();
        $response = new Response(200, [], json_encode(array('record' => array('id' => '12345'),'filename' => array('guid' => 'test.txt'))));
        $Bean->setCurrentAction(Module::BEAN_ACTION_TEMP_FILE_UPLOAD);
        $Bean->setResponse($response);
        $this->assertEquals(array(
             'filename_guid' => '12345',
             'filename' => 'test.txt'
         ), $Bean->toArray());
    }

    /**
     * @covers ::configureFileUploadQueryParams
     */
    public function testConfigureFileUploadQueryParams()
    {
        $Bean = new Module();
        $Bean->setClient(static::$client);
        static::$client->setAuth(new SugarOAuthStub());
        $Bean->setBaseUrl('http://localhost/rest/v11/');

        $ReflectedEndpoint = new \ReflectionClass(get_class($Bean));
        $deleteFileOnFail = $ReflectedEndpoint->getProperty('_deleteFileOnFail');
        $deleteFileOnFail->setAccessible(true);
        $configureFileUploadData = $ReflectedEndpoint->getMethod('configureFileUploadQueryParams');
        $configureFileUploadData->setAccessible(true);
        $data = $configureFileUploadData->invoke($Bean);
        $this->assertEquals(array(
            'format' => 'sugar-html-json',
            'delete_if_fails' => false,
        ), $data);
        $deleteFileOnFail->setValue($Bean, true);
        $data = $configureFileUploadData->invoke($Bean);
        $this->assertEquals(array(
            'format' => 'sugar-html-json',
            'delete_if_fails' => true,
            'platform' => 'base',
            'oauth_token' => 'bar'
        ), $data);

        static::$client->getAuth()->clearToken();
        $data = $configureFileUploadData->invoke($Bean);
        $this->assertEquals(array(
            'format' => 'sugar-html-json',
            'delete_if_fails' => true,
            'platform' => 'base'
        ), $data);
    }

    /**
     * @covers ::attachFile
     * @covers ::tempFile
     * @covers ::configureURL
     * @covers ::setFile
     * @covers ::clear
     * @covers ::resetUploads
     * @covers ::configureRequest
     * @covers ::configureFileUploadRequest
     */
    public function testFileAttachments()
    {
        self::$client->mockResponses->append(new Response(200, [], json_encode(['uploadfile' => 'foo'])));
        $Bean = new Module();
        $Bean->setClient(self::$client);
        $Bean->setModule('Accounts');
        $Reflection = new \ReflectionClass($Bean);
        $_upload = $Reflection->getProperty('_upload');
        $_upload->setAccessible(true);
        $_file = $Reflection->getProperty('_uploadFile');
        $_file->setAccessible(true);
        $setFileMethod = $Reflection->getMethod('setFile');
        $setFileMethod->setAccessible(true);

        $setFileMethod->invoke($Bean, 'filename', __FILE__);
        $this->assertEquals([
             'field' => 'filename',
             'path' => __FILE__
         ], $_file->getValue($Bean));
        $this->assertEquals(false, $_upload->getValue($Bean));
        $setFileMethod->invoke($Bean, 'filename', __FILE__, ['field' => 'foo','filename' => 'foobar.php']);
        $this->assertEquals([
             'field' => 'filename',
             'path' => __FILE__,
             'filename' => 'foobar.php'
         ], $_file->getValue($Bean));
        $this->assertEquals(false, $_upload->getValue($Bean));
        $this->assertEquals(false, $_upload->getValue($Bean));
        $this->assertEquals($Bean, $Bean->clear());
        $this->assertEquals([], $_file->getValue($Bean));
        $this->assertEquals(false, $_upload->getValue($Bean));

        $Bean->set('id', '12345a');
        $this->assertEquals($Bean, $Bean->attachFile('uploadfile', __FILE__));
        $this->assertEquals(Module::BEAN_ACTION_ATTACH_FILE, $Bean->getCurrentAction());
        $this->assertEquals(array(), $_file->getValue($Bean));
        $this->assertEquals(false, $_upload->getValue($Bean));
        $this->assertEmpty($Bean->getData()->toArray());
        $request = self::$client->mockResponses->getLastRequest();
        $this->assertEquals(\http_build_query([
             'format' => 'sugar-html-json',
             'delete_if_fails' => false
         ]), $request->getUri()->getQuery());
        $this->assertEquals("/rest/v11/Accounts/12345a/file/uploadfile", $request->getUri()->getPath());
        $this->assertInstanceOf(MultipartStream::class, $request->getBody());

        self::$client->mockResponses->append(new Response(200, [], json_encode(['uploadfile' => 'foo'])));
        $this->assertEquals($Bean, $Bean->tempFile('uploadfile', __FILE__, true));
        $this->assertEquals(Module::BEAN_ACTION_TEMP_FILE_UPLOAD, $Bean->getCurrentAction());
        $this->assertEquals(array(), $_file->getValue($Bean));
        $this->assertEquals(false, $_upload->getValue($Bean));
        $this->assertEquals('12345a', $Bean['id']);
        $this->assertEmpty($Bean->getData()->toArray());
        $request = self::$client->mockResponses->getLastRequest();
        $this->assertEquals(\http_build_query([
             'format' => 'sugar-html-json',
             'delete_if_fails' => true,
             'platform' => 'base'
         ]), $request->getUri()->getQuery());
        $this->assertEquals("/rest/v11/Accounts/temp/file/uploadfile", $request->getUri()->getPath());
        $this->assertInstanceOf(MultipartStream::class, $request->getBody());

        $Bean = new Module();
        $Bean->setClient(self::$client);
        $Bean->setModule('Accounts');
        self::$client->mockResponses->append(new Response(200, [], json_encode(['uploadfile' => 'foo'])));
        $this->assertEquals($Bean, $Bean->tempFile('uploadfile', __FILE__, true));
        $this->assertEquals(Module::BEAN_ACTION_TEMP_FILE_UPLOAD, $Bean->getCurrentAction());
        $this->assertEquals(array(), $_file->getValue($Bean));
        $this->assertEquals(false, $_upload->getValue($Bean));
        $this->assertEmpty($Bean['id']);
        $this->assertEmpty($Bean->getData()->toArray());
        $request = self::$client->mockResponses->getLastRequest();
        $this->assertEquals(\http_build_query([
             'format' => 'sugar-html-json',
             'delete_if_fails' => true,
             'platform' => 'base'
         ]), $request->getUri()->getQuery());
        $this->assertEquals("/rest/v11/Accounts/temp/file/uploadfile", $request->getUri()->getPath());
        $this->assertInstanceOf(MultipartStream::class, $request->getBody());
    }

    /**
     * @covers ::configurePayload
     * @covers ::configureUrl
     * @return void
     */
    public function testDuplicateCheck()
    {
        self::$client->mockResponses->append(new Response(200));
        $Bean = new Module();
        $Bean->setClient(self::$client);
        $Bean->setModule('Accounts');
        $Bean->set([
             'id' => '12345',
             'name' => 'foo',
             'bar' => 'foz'
         ]);
        $Bean->duplicateCheck();
        $request = self::$client->mockResponses->getLastRequest();
        $this->assertEquals('duplicateCheck', $Bean->getCurrentAction());
        $this->assertEquals('/rest/v11/Accounts/duplicateCheck', $request->getUri()->getPath());
        $this->assertEquals(json_encode([
             'id' => '12345',
             'name' => 'foo',
             'bar' => 'foz'
         ]), $request->getBody()->getContents());
    }

    /**
     * @covers ::downloadFile
     * @covers ::getDownloadedFile
     * @return void
     */
    public function testDownloadFile()
    {
        $stream = Utils::streamFor("test");
        self::$client->mockResponses->append(new Response(200, [], $stream));
        $Bean = new Module();
        $Bean->setClient(self::$client);
        $Bean->setModule('Notes');
        $Bean->set([
            'id' => '12345',
        ]);
        $Bean->downloadFile('uploadfile');
        $request = self::$client->mockResponses->getLastRequest();
        $this->assertEquals('downloadFile', $Bean->getCurrentAction());
        $this->assertEquals('/rest/v11/Notes/12345/file/uploadfile', $request->getUri()->getPath());
        $this->assertStringStartsWith("12345", basename($Bean->getDownloadedFile()));
        $this->assertEquals("test", file_get_contents($Bean->getDownloadedFile()));
        unlink($Bean->getDownloadedFile());
    }
}
