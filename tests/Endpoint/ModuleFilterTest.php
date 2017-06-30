<?php
/**
 * ©[2017] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace Sugarcrm\REST\Tests\Endpoint;

use MRussell\Http\Request\Curl;
use MRussell\Http\Request\JSON;
use MRussell\REST\Endpoint\Data\EndpointData;
use Sugarcrm\REST\Endpoint\Data\FilterData;
use Sugarcrm\REST\Endpoint\ModuleFilter;


/**
 * Class ModuleFilterTest
 * @package Sugarcrm\REST\Tests\Endpoint
 * @coversDefaultClass Sugarcrm\REST\Endpoint\ModuleFilter
 * @group ModuleFilterTest
 */
class ModuleFilterTest extends \PHPUnit_Framework_TestCase
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
    public function testSetOptions(){
        $ModuleFilter = new ModuleFilter();
        $this->assertEquals($ModuleFilter,$ModuleFilter->setOptions(array(
            'Accounts',
            'count'
        )));
        $this->assertEquals(array(
            'module' => 'Accounts',
            'count' => 'count'
        ),$ModuleFilter->getOptions());
        $this->assertEquals($ModuleFilter,$ModuleFilter->setOptions(array(
            'Accounts',
            true
        )));
        $this->assertEquals(array(
            'module' => 'Accounts',
            'count' => 'count'
        ),$ModuleFilter->getOptions());
        $this->assertEquals($ModuleFilter,$ModuleFilter->setOptions(array(
            'Accounts',
            0
        )));
        $this->assertEquals(array(
            'module' => 'Accounts',
            'count' => 'count'
        ),$ModuleFilter->getOptions());
        $this->assertEquals($ModuleFilter,$ModuleFilter->setOptions(array(
            'Accounts'
        )));
        $this->assertEquals(array(
            'module' => 'Accounts'
        ),$ModuleFilter->getOptions());
    }

    /**
     * @covers ::fetch
     */
    public function testFetch(){
        $ModuleFilter = new ModuleFilter();
        $ModuleFilter->setBaseUrl('http://localhost/rest/v10');
        $ModuleFilter->setModule('Accounts');
        $ModuleFilter->fetch();
        $this->assertEquals('http://localhost/rest/v10/Accounts/filter',$ModuleFilter->getRequest()->getURL());
        $properties = $ModuleFilter->getProperties();
        $this->assertEquals(JSON::HTTP_GET,$properties[$ModuleFilter::PROPERTY_HTTP_METHOD]);
        $ModuleFilter->filter();
        $properties = $ModuleFilter->getProperties();
        $this->assertEquals(JSON::HTTP_POST,$properties[$ModuleFilter::PROPERTY_HTTP_METHOD]);
        $ModuleFilter->fetch();
        $properties = $ModuleFilter->getProperties();
        $this->assertEquals(JSON::HTTP_GET,$properties[$ModuleFilter::PROPERTY_HTTP_METHOD]);
    }

    /**
     * @covers ::configureData
     */
    public function testConfigureData(){
        $ModuleFilter = new ModuleFilter();
        $Reflection = new \ReflectionClass(get_class($ModuleFilter));
        $configureData = $Reflection->getMethod('configureData');
        $configureData->setAccessible(TRUE);

        $ModuleFilter->setBaseUrl('http://localhost/rest/v10');
        $ModuleFilter->setModule('Accounts');
        $ModuleFilter->filter();
        $data = $configureData->invoke($ModuleFilter,new EndpointData());
        $this->assertArrayNotHasKey('filter',$data);

        $ModuleFilter = new ModuleFilter();
        $ModuleFilter->setBaseUrl('http://localhost/rest/v10');
        $ModuleFilter->setModule('Accounts');
        $ModuleFilter->filter()->contains('foo','bar');
        $data = $configureData->invoke($ModuleFilter,new EndpointData());
        $this->assertArrayHasKey('filter',$data);
    }

    /**
     * @covers ::configureURL
     */
    public function testConfigureUrl(){
        $ModuleFilter = new ModuleFilter();
        $ModuleFilter->setBaseUrl('http://localhost/rest/v10');
        $ModuleFilter->setModule('Accounts');
        $ModuleFilter->setProperty('httpMethod',Curl::HTTP_POST);
        $Request = $ModuleFilter->compileRequest();
        $this->assertEquals('POST',$Request->getMethod());
        $this->assertEquals('http://localhost/rest/v10/Accounts/filter',$Request->getURL());
    }

    /**
     * @covers ::filter
     * @covers Sugarcrm\REST\Endpoint\Data\FilterData
     */
    public function testFilter(){
        $ModuleFilter = new ModuleFilter();
        $ModuleFilter->setModule('Foo');
        $ModuleFilter->setBaseUrl('http://localhost/rest/v10');
        $Filter = $ModuleFilter->filter();
        $this->assertInstanceOf('Sugarcrm\\REST\\Endpoint\\Data\\FilterData',$Filter);
        $this->assertEquals($ModuleFilter,$Filter->execute());
        $this->assertEquals($Filter,$ModuleFilter->filter());
        $Filter->equals('foo','bar');
        $this->assertEquals($Filter,$ModuleFilter->filter(TRUE));
        $this->assertEquals(array(),$Filter->asArray(FALSE));
        $ModuleFilter->setData(array(
            'filter' => array(
                array(
                    '$equals' => array(
                        'bar' => 'foo'
                    )
                )
            )
        ));
        $Filter = $ModuleFilter->filter(TRUE);
        $this->assertEmpty($ModuleFilter->getData()['filter']);

        $ModuleFilter = new ModuleFilter();
        $ModuleFilter->setData(array(
            'filter' => array(
                array(
                    '$equals' => array(
                        'bar' => 'foo'
                    )
                )
            )
        ));
        $Filter = $ModuleFilter->filter();
        $this->assertEquals(array(
            array(
                '$equals' => array(
                    'bar' => 'foo'
                )
            )
        ),$Filter->asArray(FALSE));
    }

    /**
     * @covers ::count
     */
    public function testCount(){
        $ModuleFilter = new ModuleFilter();
        $ModuleFilter->setModule('Accounts');
        $ModuleFilter->setBaseUrl('http://localhost/rest/v10/');
        $this->assertEquals($ModuleFilter,$ModuleFilter->count());
        $this->assertEquals('http://localhost/rest/v10/Accounts/filter/count',$ModuleFilter->getRequest()->getURL());
    }

}
