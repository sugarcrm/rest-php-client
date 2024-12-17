<?php

/**
 * ©[2024] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace Sugarcrm\REST\Tests\Endpoint;

use PHPUnit\Framework\TestCase;
use MRussell\REST\Endpoint\Data\EndpointData;
use Sugarcrm\REST\Tests\Stubs\Endpoint\SugarBeanCollectionEndpoint;

/**
 * Class AbstractSugarBeanCollectionEndpointTest
 * @package Sugarcrm\REST\Tests\Endpoint
 * @coversDefaultClass Sugarcrm\REST\Endpoint\Abstracts\AbstractSugarBeanCollectionEndpoint
 * @group AbstractSugarBeanCollectionEndpointTest
 */
class AbstractSugarBeanCollectionEndpointTest extends TestCase
{
    public static function setUpBeforeClass(): void
    {
        //Add Setup for static properties here
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
     * @covers ::setUrlArgs
     */
    public function testSetUrlArgs()
    {
        $Endpoint = new SugarBeanCollectionEndpoint();
        $this->assertEquals($Endpoint, $Endpoint->setUrlArgs([
            'Accounts',
        ]));
        $this->assertEquals([
            'module' => 'Accounts',
        ], $Endpoint->getUrlArgs());
        $this->assertEquals($Endpoint, $Endpoint->setUrlArgs([
            'Accounts',
            'foo',
        ]));
        $this->assertEquals([
            'module' => 'Accounts',
            1 => 'foo',
        ], $Endpoint->getUrlArgs());
    }

    /**
     * @covers ::getModule
     * @covers ::setModule
     */
    public function testSetModule()
    {
        $Endpoint = new SugarBeanCollectionEndpoint();
        $this->assertEquals($Endpoint, $Endpoint->setModule('Accounts'));
        $this->assertEquals('Accounts', $Endpoint->getModule());
    }

    /**
     * @covers ::getOrderBy
     * @covers ::setOrderBy
     */
    public function testSetOrderBy()
    {
        $Endpoint = new SugarBeanCollectionEndpoint();
        $this->assertEquals('', $Endpoint->getOrderBy());
        $this->assertEquals($Endpoint, $Endpoint->setOrderBy('foo:DESC'));
        $this->assertEquals('foo:DESC', $Endpoint->getOrderBy());
    }

    /**
     * @covers ::addField
     * @covers ::getFields
     * @covers ::setFields
     * @covers ::setView
     * @covers ::getView
     * @covers ::reset
     */
    public function testSetFields()
    {
        $fields = [
            'foo',
            'bar',
            'name',
        ];
        $Endpoint = new SugarBeanCollectionEndpoint();
        $this->assertEquals($Endpoint, $Endpoint->addField('foo'));
        $this->assertEquals(['foo'], $Endpoint->getFields());
        $this->assertEquals($Endpoint, $Endpoint->addField('foo'));
        $this->assertEquals(['foo'], $Endpoint->getFields());
        $this->assertEquals($Endpoint, $Endpoint->addField('bar'));
        $this->assertEquals(['foo', 'bar'], $Endpoint->getFields());
        $this->assertEquals($Endpoint, $Endpoint->setFields($fields));
        $this->assertEquals($fields, $Endpoint->getFields());

        $this->assertEquals($Endpoint, $Endpoint->setView('list'));
        $this->assertEquals('list', $Endpoint->getView());
        $this->assertEquals($Endpoint, $Endpoint->reset());
        $this->assertEquals('', $Endpoint->getView());
        $this->assertEquals([], $Endpoint->getFields());
    }

    /**
     * @covers ::configurePayload
     */
    public function testConfigurePayload()
    {
        $Endpoint = new SugarBeanCollectionEndpoint();
        $Reflection = new \ReflectionClass(SugarBeanCollectionEndpoint::class);
        $configurePayload = $Reflection->getMethod('configurePayload');
        $configurePayload->setAccessible(true);

        $Endpoint->setOrderBy('foo:DESC');
        $this->assertArrayHasKey('order_by', $configurePayload->invoke($Endpoint, new EndpointData()));

        $Endpoint->addField('foo');
        $this->assertArrayHasKey('fields', $configurePayload->invoke($Endpoint, new EndpointData()));
        $Endpoint->addField('bar');
        $data = $configurePayload->invoke($Endpoint, new EndpointData());
        $this->assertEquals('foo,bar', $data['fields']);
    }

    /**
     * @covers ::configureURL
     */
    public function testConfigureURL()
    {
        $Endpoint = new SugarBeanCollectionEndpoint();
        $Reflection = new \ReflectionClass(SugarBeanCollectionEndpoint::class);
        $configureURL = $Reflection->getMethod('configureURL');
        $configureURL->setAccessible(true);

        $Endpoint->setProperty('url', '$module/list');
        $Endpoint->setModule('Accounts');
        $this->assertEquals('Accounts/list', $configureURL->invoke($Endpoint, []));
        $this->assertEquals('Accounts/list', $configureURL->invoke($Endpoint, ['foo']));
    }

    /**
     * @covers ::buildModel
     */
    public function testBuildModel()
    {
        $Endpoint = new SugarBeanCollectionEndpoint();
        $Reflection = new \ReflectionClass(get_class($Endpoint));
        $buildModel = $Reflection->getMethod('buildModel');
        $buildModel->setAccessible(true);

        $Endpoint->setModule('Accounts');

        $Model = $buildModel->invoke($Endpoint);
        $this->assertEquals('Accounts', $Model->getModule());

        $Endpoint = new SugarBeanCollectionEndpoint();
        $Model = $buildModel->invoke($Endpoint, [
            'id' => 12345,
            'foo' => 'bar',
            '_module' => 'Accounts',
        ]);

        $this->assertEquals('Accounts', $Model->getModule());
    }
}
