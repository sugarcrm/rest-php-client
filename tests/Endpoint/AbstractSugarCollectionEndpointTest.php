<?php

namespace Sugarcrm\REST\Tests\Endpoint;

use Sugarcrm\REST\Tests\Stubs\Endpoint\SugarCollectionEndpoint;

/**
 * Class AbstractSugarCollectionEndpointTest
 * @package Sugarcrm\REST\Tests\Endpoint
 * @coversDefaultClass Sugarcrm\REST\Endpoint\Abstracts\AbstractSugarCollectionEndpoint
 * @group AbstractSugarCollectionEndpointTest
 */
class AbstractSugarCollectionEndpointTest extends \PHPUnit\Framework\TestCase {

    public static function setUpBeforeClass(): void {
        //Add Setup for static properties here
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
     * @covers ::setOffset
     * @covers ::getOffset
     */
    public function testSetOffset() {
        $Endpoint = new SugarCollectionEndpoint();
        $this->assertEquals(0, $Endpoint->getOffset());
        $this->assertEquals($Endpoint, $Endpoint->setOffset(10));
        $this->assertEquals(10, $Endpoint->getOffset());
    }

    /**
     * @covers ::setLimit
     * @covers ::getLimit
     */
    public function testSetLimit() {
        $Endpoint = new SugarCollectionEndpoint();
        $this->assertEquals(20, $Endpoint->getLimit());
        $this->assertEquals($Endpoint, $Endpoint->setLimit(10));
        $this->assertEquals(10, $Endpoint->getLimit());
    }

    // FIXME: Needs investigation
    // public function testConfigurePayload(){
    //     $Endpoint = new SugarCollectionEndpoint();
    //     $Reflection = new \ReflectionClass('Sugarcrm\REST\Tests\Stubs\Endpoint\SugarCollectionEndpoint');
    //     $configurePayload = $Reflection->getMethod('configurePayload');
    //     $configurePayload->setAccessible(true);
    //     $this->assertEquals(array(
    //         'offset' => 0,
    //         'max_num' => 20
    //     ),$configurePayload->invoke($Endpoint,new EndpointData()));
    // }
}
