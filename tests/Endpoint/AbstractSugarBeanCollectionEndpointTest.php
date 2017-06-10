<?php
/**
 * ©[2017] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace Sugarcrm\REST\Tests\Endpoint;

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
class AbstractSugarBeanCollectionEndpointTest extends \PHPUnit_Framework_TestCase
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
     * @covers ::setOptions
     */
    public function testSetOptions()
    {
        $Endpoint = new SugarBeanCollectionEndpoint();
        $this->assertEquals($Endpoint,$Endpoint->setOptions(array(
            'Accounts'
        )));
        $this->assertEquals(array(
            'module' => 'Accounts'
        ),$Endpoint->getOptions());
        $this->assertEquals($Endpoint,$Endpoint->setOptions(array(
            'Accounts',
            'foo'
        )));
        $this->assertEquals(array(
            'module' => 'Accounts'
        ),$Endpoint->getOptions());
    }

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
     * @covers ::configureData
     */
    public function testConfigureData(){
        $Endpoint = new SugarBeanCollectionEndpoint();
        $Reflection = new \ReflectionClass('Sugarcrm\REST\Tests\Stubs\Endpoint\SugarBeanCollectionEndpoint');
        $configureData = $Reflection->getMethod('configureData');
        $configureData->setAccessible(TRUE);
        $Endpoint->setOrderBy('foo:DESC');
        $this->assertArrayHasKey('order_by',$configureData->invoke($Endpoint,new EndpointData()));
    }

    /**
     * @covers ::updateCollection
     */
    public function testUpdateCollection(){
        $Endpoint = new SugarBeanCollectionEndpoint();
        $Reflection = new \ReflectionClass('Sugarcrm\REST\Tests\Stubs\Endpoint\SugarBeanCollectionEndpoint');
        $updateCollection = $Reflection->getMethod('updateCollection');
        $updateCollection->setAccessible(TRUE);
        $Response = new JSON();
        $ReflectedResponse = new \ReflectionClass(get_class($Response));
        $body = $ReflectedResponse->getProperty('body');
        $body->setAccessible(TRUE);
        $body->setValue($Response,json_encode(array(
            'next_offset' => -1,
            'records' => array(
                array(
                    'id' => 12345,
                    'foo' => 'bar'
                ),
                array(
                    'id' => 5678,
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
            array(
                'id' => 12345,
                'foo' => 'bar'
            ),
            array(
                'id' => 5678,
                'foo' => 'baz'
            ),
            array(
                'foo' => 'foo'
            )
        ),$Endpoint->asArray());

        $Endpoint = new ModuleFilter();
        $Reflection = new \ReflectionClass(get_class($Endpoint));
        $updateCollection = $Reflection->getMethod('updateCollection');
        $updateCollection->setAccessible(TRUE);
        $Endpoint->setResponse($Response);
        $updateCollection->invoke($Endpoint);
        $this->assertEquals(array(
            12345 => array(
                'id' => 12345,
                'foo' => 'bar'
            ),
            5678 => array(
                'id' => 5678,
                'foo' => 'baz'
            ),
            array(
                'foo' => 'foo'
            )
        ),$Endpoint->asArray());
    }
}
