<?php

/**
 * ©[2024] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace Sugarcrm\REST\Tests\Endpoint\Data\Filters;

use PHPUnit\Framework\TestCase;
use Sugarcrm\REST\Endpoint\Data\Filters\Operator\Contains;
use Sugarcrm\REST\Endpoint\Data\Filters\Operator\Ends;
use Sugarcrm\REST\Endpoint\Data\Filters\Operator\Equals;
use Sugarcrm\REST\Endpoint\Data\Filters\Operator\GreaterThan;
use Sugarcrm\REST\Endpoint\Data\Filters\Operator\GreaterThanOrEqual;
use Sugarcrm\REST\Endpoint\Data\Filters\Operator\In;
use Sugarcrm\REST\Endpoint\Data\Filters\Operator\IsNull;
use Sugarcrm\REST\Endpoint\Data\Filters\Operator\LessThan;
use Sugarcrm\REST\Endpoint\Data\Filters\Operator\LessThanOrEqual;
use Sugarcrm\REST\Endpoint\Data\Filters\Operator\NotEquals;
use Sugarcrm\REST\Endpoint\Data\Filters\Operator\NotIn;
use Sugarcrm\REST\Endpoint\Data\Filters\Operator\NotNull;
use Sugarcrm\REST\Endpoint\Data\Filters\Operator\Starts;

/**
 * Class AbstractOperatorTest
 * @package Sugarcrm\REST\Tests\Endpoint\Data\Filters
 * @coversDefaultClass Sugarcrm\REST\Endpoint\Data\Filters\Operator\AbstractOperator
 * @group AbstractOperatorTest
 */
class AbstractOperatorTest extends TestCase
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
     * @covers ::__construct
     * @covers ::getValue
     * @covers ::getField
     */
    public function testConstructor()
    {
        $Operator = new Contains();
        $this->assertEmpty($Operator->getField());
        $this->assertEmpty($Operator->getValue());
        $Operator = new Contains(['foo']);
        $this->assertEquals('foo', $Operator->getField());
        $this->assertEmpty($Operator->getValue());
        $Operator = new Contains(['foo', 'bar']);
        $this->assertEquals('foo', $Operator->getField());
        $this->assertEquals('bar', $Operator->getValue());
    }

    /**
     * @covers ::setField
     * @covers ::getField
     */
    public function testSetField()
    {
        $Operator = new In();
        $this->assertEquals($Operator, $Operator->setField('foo'));
        $this->assertEquals('foo', $Operator->getField());
    }

    /**
     * @covers ::setValue
     * @covers ::getValue
     */
    public function testSetValue()
    {
        $Operator = new Starts();
        $this->assertEquals($Operator, $Operator->setValue('bar'));
        $this->assertEquals('bar', $Operator->getValue());
    }

    /**
     * @covers ::compile
     * @covers Sugarcrm\REST\Endpoint\Data\Filters\Operator\IsNull::compile
     */
    public function testCompile()
    {
        $Contains = new Contains(['foo', 'bar']);
        $this->assertEquals([
            'foo' => [
                Contains::OPERATOR => 'bar',
            ],
        ], $Contains->compile());

        $Ends = new Ends(['foo', 'bar']);
        $this->assertEquals([
            'foo' => [
                Ends::OPERATOR => 'bar',
            ],
        ], $Ends->compile());

        $Equals = new Equals(['foo', 'bar']);
        $this->assertEquals([
            'foo' => [
                Equals::OPERATOR => 'bar',
            ],
        ], $Equals->compile());

        $GreaterThan = new GreaterThan(['foo', 'bar']);
        $this->assertEquals([
            'foo' => [
                GreaterThan::OPERATOR => 'bar',
            ],
        ], $GreaterThan->compile());

        $GreaterThanOrEqual = new GreaterThanOrEqual(['foo', 'bar']);
        $this->assertEquals([
            'foo' => [
                GreaterThanOrEqual::OPERATOR => 'bar',
            ],
        ], $GreaterThanOrEqual->compile());

        $In = new In(['foo', ['1234']]);
        $this->assertEquals([
            'foo' => [
                In::OPERATOR => ['1234'],
            ],
        ], $In->compile());

        $IsNull = new IsNull(['foo', ['1234']]);
        $this->assertEquals([
            'foo' => [
                IsNull::OPERATOR,
            ],
        ], $IsNull->compile());

        $LessThan = new LessThan(['foo', '1234']);
        $this->assertEquals([
            'foo' => [
                LessThan::OPERATOR => '1234',
            ],
        ], $LessThan->compile());

        $LessThanOrEqual = new LessThanOrEqual(['foo', '1234']);
        $this->assertEquals([
            'foo' => [
                LessThanOrEqual::OPERATOR => '1234',
            ],
        ], $LessThanOrEqual->compile());

        $NotEquals = new NotEquals(['foo', 'bar']);
        $this->assertEquals([
            'foo' => [
                NotEquals::OPERATOR => 'bar',
            ],
        ], $NotEquals->compile());

        $NotIn = new NotIn(['foo', ['1234']]);
        $this->assertEquals([
            'foo' => [
                NotIn::OPERATOR => ['1234'],
            ],
        ], $NotIn->compile());

        $NotNull = new NotNull(['foo', ['1234']]);
        $this->assertEquals([
            'foo' => [
                NotNull::OPERATOR,
            ],
        ], $NotNull->compile());

        $Starts = new Starts(['foo', 'bar']);
        $this->assertEquals([
            'foo' => [
                Starts::OPERATOR => 'bar',
            ],
        ], $Starts->compile());
    }
}
