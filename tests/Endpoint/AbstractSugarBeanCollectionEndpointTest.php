<?php
/**
 * ©[2017] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace Sugarcrm\REST\Tests\Endpoint;

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

    public function testUpdateCollection(){

    }
}
