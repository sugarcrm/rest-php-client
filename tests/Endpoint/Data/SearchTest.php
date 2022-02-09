<?php

namespace Sugarcrm\REST\Tests\Endpoint\Data;

use Sugarcrm\REST\Endpoint\Search;

/**
 * Class SearchTest
 * @package Sugarcrm\REST\Tests\Endpoint\Data
 * @coversDefaultClass Sugarcrm\REST\Endpoint\Search
 * @group SearchTest
 */
class SearchTest extends \PHPUnit\Framework\TestCase {

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

    public function testGet() {
        $Search = new Search();
        $Search['12345'] = array(
            'foo' => 'bar',
            '_module' => 'Accounts'
        );
        $Model = $Search->get('12345');
        $this->assertEquals('Accounts', $Model->getModule());
        $Search['12345'] = array(
            'foo' => 'bar'
        );
        $Model = $Search->get('12345');
        $this->assertEmpty($Model->getModule());
    }
}
