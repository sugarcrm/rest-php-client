<?php

/**
 * ©[2024] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace Sugarcrm\REST\Tests\Endpoint\Data\Filters;

use PHPUnit\Framework\TestCase;
use Sugarcrm\REST\Endpoint\Data\Filters\Operator\IsNull;

/**
 * Class IsNullTest
 * @package Sugarcrm\REST\Tests\Endpoint\Data\Filters
 * @coversDefaultClass Sugarcrm\REST\Endpoint\Data\Filters\Operator\IsNull
 * @group IsNullTest
 */
class IsNullTest extends TestCase
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
     * @covers ::setvalue
     */
    public function testSetValue()
    {
        $IsNull = new IsNull();
        $this->assertEmpty($IsNull->getValue());
        $this->assertEquals($IsNull, $IsNull->setValue('foo'));
        $this->assertEquals(null, $IsNull->getValue());
    }
}
