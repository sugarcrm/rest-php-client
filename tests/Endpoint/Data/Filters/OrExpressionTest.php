<?php

/**
 * ©[2024] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace Sugarcrm\REST\Tests\Endpoint\Data\Filters;

use PHPUnit\Framework\TestCase;
use Sugarcrm\REST\Endpoint\Data\Filters\Expression\OrExpression;

/**
 * Class OrExpressionTest
 * @package Sugarcrm\REST\Tests\Endpoint\Data\Filters
 * @coversDefaultClass Sugarcrm\REST\Endpoint\Data\Filters\Expression\OrExpression
 * @group OrExpressionTest
 */
class OrExpressionTest extends TestCase
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
     * @covers ::compile
     */
    public function testCompile()
    {
        $Expression = new OrExpression();
        $this->assertArrayHasKey(OrExpression::OPERATOR, $Expression->compile());
    }
}
