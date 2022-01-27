<?php
/**
 * ©[2019] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace Sugarcrm\REST\Tests\Endpoint;

use GuzzleHttp\Psr7\Response;
use MRussell\Http\Response\JSON;
use MRussell\REST\Endpoint\Data\EndpointData;
use Sugarcrm\REST\Endpoint\ModuleFilter;
use Sugarcrm\REST\Tests\Stubs\Endpoint\SugarBeanCollectionEndpoint;


/**
 * Class AbstractSugarBeanCollectionEndpointTest
 * @package Sugarcrm\REST\Tests\Endpoint
 * @coversDefaultClass Sugarcrm\REST\Endpoint\Abstracts\AbstractSugarBeanCollectionEndpoint
 * @group AbstractSugarBeanCollectionEndpointTest
 */
class AbstractSugarBeanCollectionEndpointTest extends \PHPUnit\Framework\TestCase
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
     * @covers ::setOptions
     */
    // public function testSetOptions()
    // {
    //     $Endpoint = new SugarBeanCollectionEndpoint();
    //     $this->assertEquals($Endpoint,$Endpoint->setOptions(array(
    //         'Accounts'
    //     )));
    //     $this->assertEquals(array(
    //         'module' => 'Accounts'
    //     ),$Endpoint->getOptions());
    //     $this->assertEquals($Endpoint,$Endpoint->setOptions(array(
    //         'Accounts',
    //         'foo'
    //     )));
    //     $this->assertEquals(array(
    //         'module' => 'Accounts',
    //         1 => 'foo'
    //     ),$Endpoint->getOptions());
    // }

    /**
     * @covers ::getModule
     * @covers ::setModule
     */
    public function testSetModule(){
        $Endpoint = new SugarBeanCollectionEndpoint();
        $this->assertEquals($Endpoint,$Endpoint->setModule('Accounts'));
        $this->assertEquals('Accounts',$Endpoint->getModule());
    }

    /**
     * @covers ::getOrderBy
     * @covers ::setOrderBy
     */
    public function testSetOrderBy(){
        $Endpoint = new SugarBeanCollectionEndpoint();
        $this->assertEquals('',$Endpoint->getOrderBy());
        $this->assertEquals($Endpoint,$Endpoint->setOrderBy('foo:DESC'));
        $this->assertEquals('foo:DESC',$Endpoint->getOrderBy());
    }

    /**
     * @covers ::addField
     * @covers ::getFields
     * @covers ::setFields
     */
    public function testSetFields(){
        $fields = array(
            'foo',
            'bar',
            'name'
        );
        $Endpoint = new SugarBeanCollectionEndpoint();
        $this->assertEquals($Endpoint,$Endpoint->addField('foo'));
        $this->assertEquals(array('foo'),$Endpoint->getFields());
        $this->assertEquals($Endpoint,$Endpoint->addField('foo'));
        $this->assertEquals(array('foo'),$Endpoint->getFields());
        $this->assertEquals($Endpoint,$Endpoint->addField('bar'));
        $this->assertEquals(array('foo','bar'),$Endpoint->getFields());
        $this->assertEquals($Endpoint,$Endpoint->setFields($fields));
        $this->assertEquals($fields,$Endpoint->getFields());
    }

    /**
     * @covers ::configurePayload
     */
    public function testConfigurePayload(){
        $Endpoint = new SugarBeanCollectionEndpoint();
        $Reflection = new \ReflectionClass('Sugarcrm\REST\Tests\Stubs\Endpoint\SugarBeanCollectionEndpoint');
        $configurePayload = $Reflection->getMethod('configurePayload');
        $configurePayload->setAccessible(TRUE);
        $Endpoint->setOrderBy('foo:DESC');
        $this->assertArrayHasKey('order_by',$configurePayload->invoke($Endpoint,new EndpointData()));

        $Endpoint->addField('foo');
        $this->assertArrayHasKey('fields',$configurePayload->invoke($Endpoint,new EndpointData()));
        $Endpoint->addField('bar');
        $data = $configurePayload->invoke($Endpoint,new EndpointData());
        $this->assertEquals('foo,bar',$data['fields']);
    }

    /**
     * @covers ::configureURL
     */
    public function testConfigureURL()
    {
        $Endpoint = new SugarBeanCollectionEndpoint();
        $Reflection = new \ReflectionClass('Sugarcrm\REST\Tests\Stubs\Endpoint\SugarBeanCollectionEndpoint');
        $configureURL = $Reflection->getMethod('configureURL');
        $configureURL->setAccessible(true);
        $Endpoint->setProperty('url', '$module/list');
        $Endpoint->setModule('Accounts');
        $this->assertEquals('Accounts/list', $configureURL->invoke($Endpoint, array()));
        $this->assertEquals('Accounts/list', $configureURL->invoke($Endpoint, array('foo')));
    }

    /**
     * @covers ::updateCollection
     */
    public function testUpdateCollection(){
        $Endpoint = new SugarBeanCollectionEndpoint();
        $Reflection = new \ReflectionClass('Sugarcrm\REST\Tests\Stubs\Endpoint\SugarBeanCollectionEndpoint');
        $updateCollection = $Reflection->getMethod('updateCollection');
        $updateCollection->setAccessible(TRUE);
        $Response = new Response();
        $ReflectedResponse = new \ReflectionClass(get_class($Response));
        $body = $ReflectedResponse->getProperty('body');
        $body->setAccessible(TRUE);
        $body->setValue($Response,json_encode(array(
            'next_offset' => -1,
            'records' => array(
                array(
                    'id' => '12345',
                    'foo' => 'bar'
                ),
                array(
                    'id' => '5678',
                    'foo' => 'baz'
                ),
                array(
                    'foo' => 'foo'
                )
            )
        )));
        $Endpoint->setResponse($Response);
        $updateCollection->invoke($Endpoint);
        $this->assertEquals(array(
            '12345' => array(
                'id' => '12345',
                'foo' => 'bar'
            ),
            '5678' => array(
                'id' => '5678',
                'foo' => 'baz'
            ),
            array(
                'foo' => 'foo'
            )
        ),$Endpoint->toArray());
    }

    /**
     * @covers ::buildModel
     */
    public function testBuildModel(){
        $Endpoint = new SugarBeanCollectionEndpoint();
        $Reflection = new \ReflectionClass(get_class($Endpoint));
        $buildModel = $Reflection->getMethod('buildModel');
        $buildModel->setAccessible(TRUE);
        $Endpoint->setModule('Accounts');

        $Model = $buildModel->invoke($Endpoint);
        $this->assertEquals('Accounts',$Model->getModule());

        $Endpoint = new SugarBeanCollectionEndpoint();
        $Model = $buildModel->invoke($Endpoint,array(
            'id' => 12345,
            'foo' => 'bar',
            '_module' => 'Accounts'
        ));

        $this->assertEquals('Accounts',$Model->getModule());
    }
}
