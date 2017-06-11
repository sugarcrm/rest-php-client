<?php
/**
 * ©[2017] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace Sugarcrm\REST\Tests\Endpoint;

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
     * @covers ::get
     */
    public function testGet(){
        $ModuleFilter = new ModuleFilter();
        $ModuleFilter->setModule('Accounts');
        $ModuleFilter[12345] = array(
            'id' => 12345,
            'foo' => 'bar'
        );
        $Model = $ModuleFilter->get(12345);

        $this->assertEquals('Accounts',$Model->getModule());

        $ModuleFilter = new ModuleFilter();
        $ModuleFilter[12345] = array(
            'id' => 12345,
            'foo' => 'bar',
            '_module' => 'Accounts'
        );
        $Model = $ModuleFilter->get(12345);

        $this->assertEquals('Accounts',$Model->getModule());
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
    }

}
