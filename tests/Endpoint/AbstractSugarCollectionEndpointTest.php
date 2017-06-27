<?php
/**
 * ©[2017] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace Sugarcrm\REST\Tests\Endpoint;

use MRussell\REST\Endpoint\Data\EndpointData;
use Sugarcrm\REST\Tests\Stubs\Endpoint\SugarCollectionEndpoint;


/**
 * Class AbstractSugarCollectionEndpointTest
 * @package Sugarcrm\REST\Tests\Endpoint
 * @coversDefaultClass Sugarcrm\REST\Endpoint\Abstracts\AbstractSugarCollectionEndpoint
 * @group AbstractSugarCollectionEndpointTest
 */
class AbstractSugarCollectionEndpointTest extends \PHPUnit_Framework_TestCase
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
     * @covers ::setOffset
     * @covers ::getOffset
     */
    public function testSetOffset(){
        $Endpoint = new SugarCollectionEndpoint();
        $this->assertEquals(0,$Endpoint->getOffset());
        $this->assertEquals($Endpoint,$Endpoint->setOffset(10));
        $this->assertEquals(10,$Endpoint->getOffset());
    }

    /**
     * @covers ::setLimit
     * @covers ::getLimit
     */
    public function testSetLimit(){
        $Endpoint = new SugarCollectionEndpoint();
        $this->assertEquals(20,$Endpoint->getLimit());
        $this->assertEquals($Endpoint,$Endpoint->setLimit(10));
        $this->assertEquals(10,$Endpoint->getLimit());
    }

    public function testConfigureData(){
        $Endpoint = new SugarCollectionEndpoint();
        $Reflection = new \ReflectionClass('Sugarcrm\REST\Tests\Stubs\Endpoint\SugarCollectionEndpoint');
        $configureData = $Reflection->getMethod('configureData');
        $configureData->setAccessible(TRUE);
        $this->assertEquals(array(
            'offset' => 0,
            'max_num' => 20
        ),$configureData->invoke($Endpoint,new EndpointData()));
    }
}
