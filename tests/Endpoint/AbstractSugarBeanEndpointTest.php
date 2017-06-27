<?php
/**
 * ©[2017] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace Sugarcrm\REST\Tests\Endpoint;

use MRussell\Http\Request\JSON;
use Sugarcrm\REST\Endpoint\Module;


/**
 * Class AbstractSugarBeanEndpointTest
 * @package Sugarcrm\REST\Tests\Endpoint
 * @coversDefaultClass Sugarcrm\REST\Endpoint\Abstracts\AbstractSugarBeanEndpoint
 * @group AbstractSugarBeanEndpointTest
 */
class AbstractSugarBeanEndpointTest extends \PHPUnit_Framework_TestCase
{

    public static function setUpBeforeClass()
    {
        //Add Setup for static properties here
    }

    public static function tearDownAfterClass()
    {
        //Add Tear Down for static properties here
    }

    public function setUp()
    {
        parent::setUp();
    }

    public function tearDown()
    {
        parent::tearDown();
    }

    /**
     * @covers ::compileRequest
     */
    public function testCompileRequest(){
        $Bean = new Module();
        $Bean->setBaseUrl('http://localhost/rest/v10/');
        $Bean->setOptions(array('Foo','bar'));
        $Request = $Bean->compileRequest();
        $this->assertEquals($Bean->getRequest(),$Request);
        $this->assertEquals(JSON::HTTP_GET,$Request->getMethod());
        $this->assertEquals('http://localhost/rest/v10/Foo/bar',$Request->getURL());
        $this->assertEmpty($Request->getBody());
    }

    /**
     * @covers ::setOptions
     * @covers ::getModule
     */
    public function testSetOptions(){
        $Bean = new Module();
        $this->assertEquals($Bean,$Bean->setOptions(array(
            'Test'
        )));
        $this->assertEquals(array(
            'module' => 'Test'
        ),$Bean->getOptions());
        $this->assertEquals('Test',$Bean->getModule());
        $this->assertEquals($Bean,$Bean->setOptions(array(
            'Test',
            '123-abc'
        )));
        $this->assertEquals(array(
            'module' => 'Test',
            'id' => '123-abc'
        ),$Bean->getOptions());
        $this->assertEquals('Test',$Bean->getModule());
        $this->assertEquals($Bean,$Bean->setOptions(array(
            'Test',
            '123-abc',
            'foo'
        )));
        $this->assertEquals(array(
            2 => 'foo',
            'module' => 'Test',
            'id' => '123-abc'
        ),$Bean->getOptions());
        $this->assertEquals('Test',$Bean->getModule());
    }

    /**
     * @covers ::setModule
     */
    public function testSetModule(){
        $Bean = new Module();
        $this->assertEquals($Bean,$Bean->setModule('Test'));
        $this->assertEquals('Test',$Bean->getModule());
    }

    /**
     * @covers ::relate
     */
    public function testRelate(){
        $Bean = new Module();
        $Bean->setBaseUrl('http://localhost/rest/v10/');
        $Bean->setOptions(array('Foo','bar'));
        $this->assertEquals($Bean,$Bean->relate('baz','foz'));
        $this->assertEquals('http://localhost/rest/v10/Foo/bar/link/baz/foz',$Bean->getRequest()->getURL());
        $this->assertEquals('POST',$Bean->getRequest()->getMethod());
    }

    /**
     * @covers ::files
     */
    public function testFiles(){
        $Bean = new Module();
        $Bean->setBaseUrl('http://localhost/rest/v10/');
        $Bean->setOptions(array('Foo','bar'));
        $this->assertEquals($Bean,$Bean->files());
        $this->assertEquals('http://localhost/rest/v10/Foo/bar/file',$Bean->getRequest()->getURL());
        $this->assertEquals('GET',$Bean->getRequest()->getMethod());
    }

    /**
     * @covers ::getFile
     */
    public function testGetFile(){
        $Bean = new Module();
        $Bean->setBaseUrl('http://localhost/rest/v10/');
        $Bean->setOptions(array('Foo','bar'));
        $this->assertEquals($Bean,$Bean->getFile('uploadfile'));
        $this->assertEquals('http://localhost/rest/v10/Foo/bar/file/uploadfile',$Bean->getRequest()->getURL());
        $this->assertEquals('GET',$Bean->getRequest()->getMethod());
    }

    /**
     * @covers ::massRelate
     */
    public function testMassRelated(){
        $Bean = new Module();
        $Bean->setBaseUrl('http://localhost/rest/v10/');
        $Bean->setOptions(array('Foo','bar'));
        $this->assertEquals($Bean,$Bean->massRelate('baz',array('1234','5678')));
        $this->assertEquals('http://localhost/rest/v10/Foo/bar/link',$Bean->getRequest()->getURL());
        $this->assertEquals('POST',$Bean->getRequest()->getMethod());
        $this->assertEquals(array(
            'link_name' => 'baz',
            'ids' => array(
                '1234',
                '5678'
            )
        ),$Bean->getData()->asArray());
    }

    /**
     * @covers ::getRelated
     */
    public function testGetRelated(){
        $Bean = new Module();
        $Bean->setBaseUrl('http://localhost/rest/v10/');
        $Bean->setOptions(array('Foo','bar'));
        $this->assertEquals($Bean,$Bean->getRelated('test'));
        $this->assertEquals('http://localhost/rest/v10/Foo/bar/link/test',$Bean->getRequest()->getURL());
        $this->assertEquals('GET',$Bean->getRequest()->getMethod());
    }

    /**
     * @covers ::filterRelated
     * @covers Sugarcrm\REST\Endpoint\Data\FilterData::execute
     */
    public function testFilterRelated(){
        $Bean = new Module();
        $Bean->setBaseUrl('http://localhost/rest/v10/');
        $Bean->setOptions(array('Foo','bar'));
        $Filter = $Bean->filterRelated('test');
        $this->assertInstanceOf('Sugarcrm\\REST\\Endpoint\\Data\\FilterData',$Filter);
        $this->assertEquals($Bean,$Filter->execute());
        $this->assertEquals('http://localhost/rest/v10/Foo/bar/link/test',$Bean->getRequest()->getURL());
        $this->assertEquals('GET',$Bean->getRequest()->getMethod());
        $this->assertArrayHasKey('filter',$Bean->getRequest()->getBody());
    }

    /**
     * @covers ::configureURL
     */
    public function testConfigureURL(){
        $options = array('Foo','bar');
        $Bean = new Module();
        $Bean->setBaseUrl('http://localhost/rest/v10/');
        $Bean->setOptions($options);
        $ReflectedBean = new \ReflectionClass('Sugarcrm\REST\Endpoint\Module');
        $configureUrl = $ReflectedBean->getMethod('configureURL');
        $configureUrl->setAccessible(TRUE);

        $Bean->setCurrentAction(Module::MODEL_ACTION_RETRIEVE);
        $this->assertEquals('Foo/bar',$configureUrl->invoke($Bean,$Bean->getOptions()));
        $Bean->setCurrentAction(Module::MODEL_ACTION_UPDATE);
        $this->assertEquals('Foo/bar',$configureUrl->invoke($Bean,$Bean->getOptions()));
        $Bean->setCurrentAction(Module::MODEL_ACTION_DELETE);
        $this->assertEquals('Foo/bar',$configureUrl->invoke($Bean,$Bean->getOptions()));
        $Bean->setCurrentAction(Module::MODEL_ACTION_CREATE);
        $this->assertEquals('Foo',$configureUrl->invoke($Bean,$Bean->getOptions()));
        $Bean->setCurrentAction(Module::BEAN_ACTION_UNFOLLOW);
        $this->assertEquals('Foo/bar/unsubscribe',$configureUrl->invoke($Bean,$Bean->getOptions()));
        $Bean->setCurrentAction(Module::BEAN_ACTION_UNFAVORITE);
        $this->assertEquals('Foo/bar/unfavorite',$configureUrl->invoke($Bean,$Bean->getOptions()));
        $Bean->setCurrentAction(Module::BEAN_ACTION_FAVORITE);
        $this->assertEquals('Foo/bar/favorite',$configureUrl->invoke($Bean,$Bean->getOptions()));
        $Bean->setCurrentAction(Module::BEAN_ACTION_FOLLOW);
        $this->assertEquals('Foo/bar/subscribe',$configureUrl->invoke($Bean,$Bean->getOptions()));
        $Bean->setCurrentAction(Module::BEAN_ACTION_FILE);
        $this->assertEquals('Foo/bar/file',$configureUrl->invoke($Bean,$Bean->getOptions()));
        $Bean->setCurrentAction(Module::BEAN_ACTION_AUDIT);
        $this->assertEquals('Foo/bar/audit',$configureUrl->invoke($Bean,$Bean->getOptions()));

        //More Options Needed
        $options[] = 'baz';
        $Bean->setOptions($options);
        $Bean->setCurrentAction(Module::BEAN_ACTION_CREATE_RELATED);
        $this->assertEquals('Foo/bar/link/baz',$configureUrl->invoke($Bean,$Bean->getOptions()));
        $options[] = 'foz';
        $Bean->setOptions($options);
        $Bean->setCurrentAction(Module::BEAN_ACTION_UNLINK);
        $this->assertEquals('Foo/bar/link/baz/foz',$configureUrl->invoke($Bean,$Bean->getOptions()));
        $Bean->setCurrentAction(Module::BEAN_ACTION_RELATE);
        $this->assertEquals('Foo/bar/link/baz/foz',$configureUrl->invoke($Bean,$Bean->getOptions()));
        $options = array('Foo','bar','uploadFile');
        $Bean->setOptions($options);
        $Bean->setCurrentAction(Module::BEAN_ACTION_ATTACH_FILE);
        $this->assertEquals('Foo/bar/file/uploadFile',$configureUrl->invoke($Bean,$Bean->getOptions()));
        $Bean->setCurrentAction(Module::BEAN_ACTION_DOWNLOAD_FILE);
        $this->assertEquals('Foo/bar/file/uploadFile',$configureUrl->invoke($Bean,$Bean->getOptions()));
        $options = array('Foo','bar',
                         'action' => 'test');
        $Bean->setCurrentAction(Module::MODEL_ACTION_RETRIEVE);
        unset($Bean[$Bean->modelIdKey()]);
        $this->assertEquals('Foo/bar',$configureUrl->invoke($Bean,$options));
    }

    /**
     * @covers ::configureAction
     */
    public function testConfigureAction(){
        $Bean = new Module();
        $Bean->setBaseUrl('http://localhost/rest/v10/');
        $ReflectedBean = new \ReflectionClass('Sugarcrm\REST\Endpoint\Module');
        $configureAction = $ReflectedBean->getMethod('configureAction');
        $configureAction->setAccessible(TRUE);

        $Bean->setOptions(array('Test','1234'));
        $configureAction->invoke($Bean,Module::BEAN_ACTION_RELATE,array('foo','bar'));
        $this->assertEquals('Test',$Bean->getModule());
        $this->assertEquals(array(
            'module' => 'Test',
            'id' => '1234',
            'actionArg1' => 'foo',
            'actionArg2' => 'bar'
        ),$Bean->getOptions());

        $Bean->setOptions(array('Test','1234'));
        $configureAction->invoke($Bean,Module::BEAN_ACTION_ATTACH_FILE,array('fileField'));
        $this->assertEquals('Test',$Bean->getModule());
        $this->assertEquals(array(
            'module' => 'Test',
            'id' => '1234',
            'actionArg1' => 'fileField',
        ),$Bean->getOptions());

        $Bean->setOptions(array('Test','1234'));
        $configureAction->invoke($Bean,Module::BEAN_ACTION_DOWNLOAD_FILE,array('fileField'));
        $this->assertEquals('Test',$Bean->getModule());
        $this->assertEquals(array(
            'module' => 'Test',
            'id' => '1234',
            'actionArg1' => 'fileField',
        ),$Bean->getOptions());

        $Bean->setOptions(array('Test','1234'));
        $configureAction->invoke($Bean,Module::BEAN_ACTION_UNLINK,array('foo','bar'));
        $this->assertEquals('Test',$Bean->getModule());
        $this->assertEquals(array(
            'module' => 'Test',
            'id' => '1234',
            'actionArg1' => 'foo',
            'actionArg2' => 'bar'
        ),$Bean->getOptions());

        $Bean->setOptions(array('Test','1234'));
        $configureAction->invoke($Bean,Module::BEAN_ACTION_CREATE_RELATED,array('foo','bar','baz'));
        $this->assertEquals('Test',$Bean->getModule());
        $this->assertEquals(array(
            'module' => 'Test',
            'id' => '1234',
            'actionArg1' => 'foo',
            'actionArg2' => 'bar',
            'actionArg3' => 'baz'
        ),$Bean->getOptions());

        $Bean->setOptions(array('Test','1234'));
        $configureAction->invoke($Bean,Module::MODEL_ACTION_CREATE,array('foo','bar','baz'));
        $this->assertEquals('Test',$Bean->getModule());
        $this->assertEquals(array(
            'module' => 'Test',
            'id' => '1234'
        ),$Bean->getOptions());
    }

    /**
     * @covers ::updateModel
     */
    public function testUpdateModel(){
        $Bean = new Module();
        $Bean->setBaseUrl('http://localhost/rest/v10/');
        $ReflectedResponse = new \ReflectionClass('MRussell\Http\Response\JSON');
        $body = $ReflectedResponse->getProperty('body');
        $body->setAccessible(TRUE);
        $body->setValue($Bean->getResponse(),json_encode(array('foo' => 'bar','baz' => 'foz')));

        $ReflectedBean = new \ReflectionClass('Sugarcrm\REST\Endpoint\Module');
        $updateModel = $ReflectedBean->getMethod('updateModel');
        $updateModel->setAccessible(TRUE);

        $Bean->setCurrentAction(Module::BEAN_ACTION_FAVORITE);
        $updateModel->invoke($Bean);
        $this->assertEquals(array(
            'foo' => 'bar',
            'baz' => 'foz'
        ),$Bean->asArray());
        $body->setValue($Bean->getResponse(),json_encode(array('foo' => 'foz','baz' => 'bar','favorite' => 0)));
        $Bean->setCurrentAction(Module::BEAN_ACTION_UNFAVORITE);
        $updateModel->invoke($Bean);
        $this->assertEquals(array(
            'foo' => 'foz',
            'baz' => 'bar',
            'favorite' => 0
        ),$Bean->asArray());

        $body->setValue($Bean->getResponse(),json_encode(array('foo' => 'bar','baz' => 'foz')));
        $Bean->setCurrentAction(Module::BEAN_ACTION_AUDIT);
        $updateModel->invoke($Bean);
        $this->assertEquals(array(
            'foo' => 'foz',
            'baz' => 'bar',
            'favorite' => 0
        ),$Bean->asArray());
    }
}

