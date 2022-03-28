<?php

/**
 * ©[2022] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace Sugarcrm\REST\Tests\Endpoint;

use GuzzleHttp\Psr7\Response;
use Sugarcrm\REST\Endpoint\Module;
use Sugarcrm\REST\Tests\Stubs\Auth\SugarOAuthStub;
use Sugarcrm\REST\Tests\Stubs\Client\Client;

/**
 * Class AbstractSugarBeanEndpointTest
 * @package Sugarcrm\REST\Tests\Endpoint
 * @coversDefaultClass Sugarcrm\REST\Endpoint\Abstracts\AbstractSugarBeanEndpoint
 * @group AbstractSugarBeanEndpointTest
 */
class AbstractSugarBeanEndpointTest extends \PHPUnit\Framework\TestCase {
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
     * @covers ::compileRequest
     */
    public function testCompileRequest() {
        $Bean = new Module();
        $Bean->setBaseUrl('http://localhost/rest/v10/');
        $Bean->setUrlArgs(['Foo', 'bar']);
        $Request = $Bean->compileRequest();
        $this->assertEquals("GET", $Request->getMethod());
        $this->assertEquals('http://localhost/rest/v10/Foo/bar', $Request->getUri()->__toString());
        $this->assertEmpty($Request->getBody()->getContents());
    }

    /**
     * @covers ::setUrlArgs
     * @covers ::getModule
     */
    public function testSetUrlArgs() {
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
            'id' => '123-abc'
        ), $Bean->getUrlArgs());
        $this->assertEquals('Test', $Bean->getModule());
        $this->assertEquals($Bean, $Bean->setUrlArgs(array(
            'Test',
            '123-abc',
            'foo'
        )));
        $this->assertEquals(array(
            2 => 'foo',
            'module' => 'Test',
            'id' => '123-abc'
        ), $Bean->getUrlArgs());
        $this->assertEquals('Test', $Bean->getModule());
    }

    /**
     * @covers ::setModule
     */
    public function testSetModule() {
        $Bean = new Module();
        $this->assertEquals($Bean, $Bean->setModule('Test'));
        $this->assertEquals('Test', $Bean->getModule());
    }

    /**
     * @covers ::relate
     */
    public function testRelate() {
        self::$client->mockResponses->append(new Response(200));
        $Bean = new Module();
        $Bean->setClient(self::$client);
        $Bean->setBaseUrl('http://localhost/rest/v10/');
        $Bean->setUrlArgs(['Foo', 'bar']);
        $this->assertEquals($Bean, $Bean->relate('baz', 'foz'));
        $request = self::$client->mockResponses->getLastRequest();
        $this->assertEquals('/rest/v10/Foo/bar/link/baz/foz', $request->getUri()->getPath());
        $this->assertEquals('POST', $request->getMethod());
    }

    /**
     * @covers ::files
     */
    public function testFiles() {
        $Bean = new Module();
        self::$client->mockResponses->append(new Response(200));
        $Bean->setClient(self::$client);
        $Bean->setBaseUrl('http://localhost/rest/v10/');
        $Bean->setUrlArgs(['Foo', 'bar']);
        $this->assertEquals($Bean, $Bean->files());
        $request = self::$client->mockResponses->getLastRequest();
        $this->assertEquals('/rest/v10/Foo/bar/file', $request->getUri()->getPath());
        $this->assertEquals('GET', $request->getMethod());
    }

    /**
     * @covers ::getFile
     */
    public function testGetFile() {
        $Bean = new Module();
        self::$client->container = [];
        self::$client->mockResponses->append(new Response(200));
        $Bean->setClient(self::$client);
        $Bean->setBaseUrl('http://localhost/rest/v10/');
        $Bean->setUrlArgs(['Foo', 'bar']);
        $this->assertEquals($Bean, $Bean->getFile('uploadfile'));
        $request = self::$client->mockResponses->getLastRequest();
        $this->assertEquals('http://localhost/rest/v10/Foo/bar/file/uploadfile', $request->getUri()->__toString());
        $this->assertEquals('GET', $request->getMethod());
    }

    /**
     * @covers ::massRelate
     */
    public function testMassRelated() {
        $Bean = new Module();
        self::$client->container = [];
        self::$client->mockResponses->append(new Response(200));
        $Bean->setClient(self::$client);
        $Bean->setBaseUrl('http://localhost/rest/v10/');
        $Bean->setUrlArgs(['Foo', 'bar']);

        $this->assertEquals($Bean, $Bean->massRelate('baz', ['1234', '5678']));
        $request = current(self::$client->container)['request'];
        $this->assertEquals('http://localhost/rest/v10/Foo/bar/link', $request->getUri()->__toString());
        $this->assertEquals('POST', $request->getMethod());
        
        $this->assertEquals('{"link_name":"baz","ids":["1234","5678"]}', $request->getBody()->getContents());
    }

    /**
     * @covers ::follow
     */
    public function testFollow() {
        $Bean = new Module();
        self::$client->container = [];
        self::$client->mockResponses->append(new Response(200));
        $Bean->setClient(self::$client);
        $Bean->setBaseUrl('http://localhost/rest/v10/');
        $Bean->setUrlArgs(['Foo', 'bar']);
        $Bean->follow();
        $request = current(self::$client->container)['request'];
        $this->assertEquals('http://localhost/rest/v10/Foo/bar/subscribe', $request->getUri()->__toString());
        $this->assertEquals('POST', $request->getMethod());
    }

    /**
     * @covers ::unfollow
     */
    public function testUnfollow() {
        $Bean = new Module();
        self::$client->container = [];
        self::$client->mockResponses->append(new Response(200));
        $Bean->setClient(self::$client);
        $Bean->setBaseUrl('http://localhost/rest/v10/');
        $Bean->setUrlArgs(['Foo', 'bar']);
        $Bean->unfollow();
        $request = current(self::$client->container)['request'];
        $this->assertEquals('http://localhost/rest/v10/Foo/bar/unsubscribe', $request->getUri()->__toString());
        $this->assertEquals('DELETE', $request->getMethod());
    }

    /**
     * @covers ::getRelated
     */
    public function testGetRelated() {
        $Bean = new Module();
        self::$client->mockResponses->append(new Response(200));
        $Bean->setClient(self::$client);
        $Bean->setBaseUrl('http://localhost/rest/v10/');
        $Bean->setUrlArgs(['Foo', 'bar']);
        $this->assertEquals($Bean, $Bean->getRelated('test'));
        $this->assertEquals('http://localhost/rest/v10/Foo/bar/link/test', self::$client->mockResponses->getLastRequest()->getUri()->__toString());
        $this->assertEquals('GET', self::$client->mockResponses->getLastRequest()->getMethod());
        
        self::$client->mockResponses->append(new Response(200));
        $this->assertEquals($Bean, $Bean->getRelated('test', true));
        $this->assertEquals('http://localhost/rest/v10/Foo/bar/link/test/count', self::$client->mockResponses->getLastRequest()->getUri()->__toString());
        $this->assertEquals('GET', self::$client->mockResponses->getLastRequest()->getMethod());
    }

    /**
     * @covers ::filterRelated
     * @covers Sugarcrm\REST\Endpoint\Data\FilterData::execute
     */
    public function testFilterRelated() {
        $Bean = new Module();
        self::$client->mockResponses->append(new Response(200));
        $Bean->setClient(self::$client);
        $Bean->setBaseUrl('http://localhost/rest/v10/');
        $Bean->setUrlArgs(['Foo', 'bar']);
        $Filter = $Bean->filterRelated('test');
        $this->assertInstanceOf('Sugarcrm\\REST\\Endpoint\\Data\\FilterData', $Filter);
        $this->assertEquals($Bean, $Filter->execute());
        $this->assertEquals('http://localhost/rest/v10/Foo/bar/link/test', self::$client->mockResponses->getLastRequest()->getUri()->__toString());
        $this->assertEquals('GET', self::$client->mockResponses->getLastRequest()->getMethod());
        
        self::$client->mockResponses->append(new Response(200));
        $Filter = $Bean->filterRelated('test', true);
        $Filter->equals('name','foobar');
        $this->assertInstanceOf('Sugarcrm\\REST\\Endpoint\\Data\\FilterData', $Filter);
        $this->assertEquals($Bean, $Filter->execute());
        $this->assertEquals('/rest/v10/Foo/bar/link/test/count', self::$client->mockResponses->getLastRequest()->getUri()->getPath());
        $this->assertEquals('GET', self::$client->mockResponses->getLastRequest()->getMethod());
        parse_str(self::$client->mockResponses->getLastRequest()->getUri()->getQuery(),$query);
        $this->assertArrayHasKey('filter', $query);
    }

    /**
     * @covers ::configureURL
     */
    public function testConfigureURL() {
        $options = ['Foo', 'bar'];
        $Bean = new Module();
        $Bean->setBaseUrl('http://localhost/rest/v10/');
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
    public function testConfigureAction() {
        $Bean = new Module();
        $Bean->setBaseUrl('http://localhost/rest/v10/');
        $ReflectedBean = new \ReflectionClass('Sugarcrm\REST\Endpoint\Module');
        $configureAction = $ReflectedBean->getMethod('configureAction');
        $configureAction->setAccessible(true);
        $Bean->setUrlArgs(array('Test', '1234'));
        $configureAction->invoke($Bean, Module::BEAN_ACTION_RELATE, array('foo', 'bar'));
        $this->assertEquals('Test', $Bean->getModule());
        $this->assertEquals(array(
            'module' => 'Test',
            'id' => '1234',
            'actionArg1' => 'foo',
            'actionArg2' => 'bar'
        ), $Bean->getUrlArgs());

        $Bean->setUrlArgs(array('Test', '1234'));
        $configureAction->invoke($Bean, Module::BEAN_ACTION_ATTACH_FILE, array('fileField'));
        $this->assertEquals('Test', $Bean->getModule());
        $this->assertEquals(array(
            'module' => 'Test',
            'id' => '1234',
            'actionArg1' => 'fileField',
        ), $Bean->getUrlArgs());

        $Bean->setUrlArgs(array('Test', '1234'));
        $configureAction->invoke($Bean, Module::BEAN_ACTION_DOWNLOAD_FILE, array('fileField'));
        $this->assertEquals('Test', $Bean->getModule());
        $this->assertEquals(array(
            'module' => 'Test',
            'id' => '1234',
            'actionArg1' => 'fileField',
        ), $Bean->getUrlArgs());

        $Bean->setUrlArgs(array('Test', '1234'));
        $configureAction->invoke($Bean, Module::BEAN_ACTION_UNLINK, array('foo', 'bar'));
        $this->assertEquals('Test', $Bean->getModule());
        $this->assertEquals(array(
            'module' => 'Test',
            'id' => '1234',
            'actionArg1' => 'foo',
            'actionArg2' => 'bar'
        ), $Bean->getUrlArgs());

        $Bean->setUrlArgs(array('Test', '1234'));
        $configureAction->invoke($Bean, Module::BEAN_ACTION_CREATE_RELATED, array('foo', 'bar', 'baz'));
        $this->assertEquals('Test', $Bean->getModule());
        $this->assertEquals(array(
            'module' => 'Test',
            'id' => '1234',
            'actionArg1' => 'foo',
            'actionArg2' => 'bar',
            'actionArg3' => 'baz'
        ), $Bean->getUrlArgs());

        $Bean->setUrlArgs(array('Test', '1234'));
        $configureAction->invoke($Bean, Module::MODEL_ACTION_CREATE, array('foo', 'bar', 'baz'));
        $this->assertEquals('Test', $Bean->getModule());
        $this->assertEquals(array(
            'module' => 'Test',
            'id' => '1234'
        ), $Bean->getUrlArgs());
    }

    // FIXME: Looks like Response handling has to be reviewed here and test needs to be re-written
    // TODO: Use MockResponse Handler and build out JSON Encoded responses
    /**
     * @covers ::updateModel
     */
    // public function testUpdateModel(){
    //     $Bean = new Module();
    //     $Bean->setBaseUrl('http://localhost/rest/v10/');
    //     $ReflectedResponse = new \ReflectionClass('MRussell\Http\Response\JSON');
    //     $body = $ReflectedResponse->getProperty('body');
    //     $body->setAccessible(true);
    //     $body->setValue($Bean->getResponse(),json_encode(array('foo' => 'bar','baz' => 'foz')));

    //     $ReflectedBean = new \ReflectionClass('Sugarcrm\REST\Endpoint\Module');
    //     $updateModel = $ReflectedBean->getMethod('updateModel');
    //     $updateModel->setAccessible(true);

    //     $Bean->setCurrentAction(Module::BEAN_ACTION_FAVORITE);
    //     $updateModel->invoke($Bean);
    //     $this->assertEquals(array(
    //         'foo' => 'bar',
    //         'baz' => 'foz'
    //     ),$Bean->asArray());
    //     $body->setValue($Bean->getResponse(),json_encode(array('foo' => 'foz','baz' => 'bar','favorite' => 0)));
    //     $Bean->setCurrentAction(Module::BEAN_ACTION_UNFAVORITE);
    //     $updateModel->invoke($Bean);
    //     $this->assertEquals(array(
    //         'foo' => 'foz',
    //         'baz' => 'bar',
    //         'favorite' => 0
    //     ),$Bean->asArray());

    //     $body->setValue($Bean->getResponse(),json_encode(array('foo' => 'bar','baz' => 'foz')));
    //     $Bean->setCurrentAction(Module::BEAN_ACTION_AUDIT);
    //     $updateModel->invoke($Bean);
    //     $this->assertEquals(array(
    //         'foo' => 'foz',
    //         'baz' => 'bar',
    //         'favorite' => 0
    //     ),$Bean->asArray());

    //     $Bean->reset();
    //     $body->setValue($Bean->getResponse(),json_encode(array('record' => array('id' => '12345'),'filename' => array('guid' => 'test.txt'))));
    //     $Bean->setCurrentAction(Module::BEAN_ACTION_TEMP_FILE_UPLOAD);
    //     $updateModel->invoke($Bean);
    //     $this->assertEquals(array(
    //         'filename_guid' => '12345',
    //         'filename' => 'test.txt'
    //     ),$Bean->asArray());
    // }

    /**
     * @covers ::configureFileUploadData
     */
    public function testConfigureFileUploadData() {
        $Bean = new Module();
        $Bean->setClient(static::$client);
        static::$client->setAuth(new SugarOAuthStub());
        $Bean->setBaseUrl('http://localhost/rest/v10/');

        $ReflectedEndpoint = new \ReflectionClass(get_class($Bean));
        $configureFileUploadData = $ReflectedEndpoint->getMethod('configureFileUploadData');
        $configureFileUploadData->setAccessible(true);
        $configureFileUploadData->invoke($Bean, false);
        $this->assertEquals(array(
            'format' => 'sugar-html-json',
            'delete_if_fails' => false,
        ), $Bean->getData()->toArray());
        $configureFileUploadData->invoke($Bean, true);
        $this->assertEquals(array(
            'format' => 'sugar-html-json',
            'delete_if_fails' => true,
            'oauth_token' => 'bar'
        ), $Bean->getData()->toArray());
    }

    /**
     * @covers ::attachFile
     * @covers ::tempFile
     * @covers ::addFile
     * @covers ::resetUploads
     * @covers ::configureUploads
     */
    // FIXME: mrussell to review
    // public function testFileAttachments() {
    //     self::$client->mockResponses->append(new Response(200, [], json_encode(['uploadfile' => 'foo'])));
    //     $Bean = new Module();
    //     $Bean->setClient(self::$client);
    //     $Bean->setBaseUrl('http://localhost/rest/v10/');
    //     $Bean->set('id', '12345a');
    //     $Bean->setModule('Accounts');
    //     $Reflection = new \ReflectionClass('Sugarcrm\REST\Endpoint\Abstracts\AbstractSugarBeanEndpoint');
    //     $upload = $Reflection->getProperty('upload');
    //     $upload->setAccessible(true);
    //     $_files = $Reflection->getProperty('_files');
    //     $_files->setAccessible(true);
    //     // $Auth = new SugarOAuthStub();
    //     // $Bean->setAuth($Auth);
    //     $this->assertEquals($Bean, $Bean->attachFile('uploadfile', __FILE__));
    //     $this->assertEquals(Module::BEAN_ACTION_ATTACH_FILE, $Bean->getCurrentAction());
    //     $this->assertEquals(array(), $_files->getValue($Bean));
    //     $this->assertEquals(true, $upload->getValue($Bean));
    //     $this->assertEmpty($Bean->getData()->toArray());
    //     $rBody = $Bean->getRequest()->getBody()->getContents();
    //     // print_r($rBody);
    //     $this->assertNotEmpty($rBody['uploadfile']);
    //     $this->assertEquals($Bean, $Bean->tempFile('uploadfile', __FILE__));
    //     $this->assertEquals(Module::BEAN_ACTION_TEMP_FILE_UPLOAD, $Bean->getCurrentAction());
    //     $this->assertEquals(array(), $_files->getValue($Bean));
    //     $this->assertEquals(true, $upload->getValue($Bean));
    //     $this->assertEmpty($Bean->getData()->toArray());
    //     $this->assertEquals('temp', $Bean['id']);
    //     $rBody = $Bean->getRequest()->getBody();
    //     $this->assertNotEmpty($rBody['uploadfile']);
    // }
}
