<?php

/**
 * ©[2024] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace Sugarcrm\REST\Tests\Endpoint\Data;

use PHPUnit\Framework\TestCase;
use Sugarcrm\REST\Endpoint\Search;

/**
 * Class SearchTest
 * @package Sugarcrm\REST\Tests\Endpoint\Data
 * @coversDefaultClass Sugarcrm\REST\Endpoint\Search
 * @group SearchTest
 */
class SearchTest extends TestCase
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

    public function testGet()
    {
        $Search = new Search();
        $Search['12345'] = [
            'foo' => 'bar',
            '_module' => 'Accounts',
        ];
        $Model = $Search->get('12345');
        $this->assertEquals('Accounts', $Model->getModule());
        $Search['12345'] = [
            'foo' => 'bar',
        ];
        $Model = $Search->get('12345');
        $this->assertEmpty($Model->getModule());
    }
}
