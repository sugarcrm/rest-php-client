<?php

namespace Sugarcrm\REST\Tests\Endpoint\Data\Filters;

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
class AbstractOperatorTest extends \PHPUnit\Framework\TestCase {

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
     * @covers ::__construct
     * @covers ::getValue
     * @covers ::getField
     */
    public function testConstructor() {
        $Operator = new Contains();
        $this->assertEmpty($Operator->getField());
        $this->assertEmpty($Operator->getValue());
        $Operator = new Contains(array('foo'));
        $this->assertEquals('foo', $Operator->getField());
        $this->assertEmpty($Operator->getValue());
        $Operator = new Contains(array('foo', 'bar'));
        $this->assertEquals('foo', $Operator->getField());
        $this->assertEquals('bar', $Operator->getValue());
    }

    /**
     * @covers ::setField
     * @covers ::getField
     */
    public function testSetField() {
        $Operator = new In();
        $this->assertEquals($Operator, $Operator->setField('foo'));
        $this->assertEquals('foo', $Operator->getField());
    }

    /**
     * @covers ::setValue
     * @covers ::getValue
     */
    public function testSetValue() {
        $Operator = new Starts();
        $this->assertEquals($Operator, $Operator->setValue('bar'));
        $this->assertEquals('bar', $Operator->getValue());
    }

    /**
     * @covers ::compile
     * @covers Sugarcrm\REST\Endpoint\Data\Filters\Operator\IsNull::compile
     */
    public function testCompile() {
        $Contains = new Contains(array('foo', 'bar'));
        $this->assertEquals(array(
            'foo' => array(
                Contains::OPERATOR => 'bar'
            )
        ), $Contains->compile());

        $Ends = new Ends(array('foo', 'bar'));
        $this->assertEquals(array(
            'foo' => array(
                Ends::OPERATOR => 'bar'
            )
        ), $Ends->compile());

        $Equals = new Equals(array('foo', 'bar'));
        $this->assertEquals(array(
            'foo' => array(
                Equals::OPERATOR => 'bar'
            )
        ), $Equals->compile());

        $GreaterThan = new GreaterThan(array('foo', 'bar'));
        $this->assertEquals(array(
            'foo' => array(
                GreaterThan::OPERATOR => 'bar'
            )
        ), $GreaterThan->compile());

        $GreaterThanOrEqual = new GreaterThanOrEqual(array('foo', 'bar'));
        $this->assertEquals(array(
            'foo' => array(
                GreaterThanOrEqual::OPERATOR => 'bar'
            )
        ), $GreaterThanOrEqual->compile());

        $In = new In(array('foo', array('1234')));
        $this->assertEquals(array(
            'foo' => array(
                In::OPERATOR => array('1234')
            )
        ), $In->compile());

        $IsNull = new IsNull(array('foo', array('1234')));
        $this->assertEquals(array(
            'foo' => array(
                IsNull::OPERATOR
            )
        ), $IsNull->compile());

        $LessThan = new LessThan(array('foo', '1234'));
        $this->assertEquals(array(
            'foo' => array(
                LessThan::OPERATOR => '1234'
            )
        ), $LessThan->compile());

        $LessThanOrEqual = new LessThanOrEqual(array('foo', '1234'));
        $this->assertEquals(array(
            'foo' => array(
                LessThanOrEqual::OPERATOR => '1234'
            )
        ), $LessThanOrEqual->compile());

        $NotEquals = new NotEquals(array('foo', 'bar'));
        $this->assertEquals(array(
            'foo' => array(
                NotEquals::OPERATOR => 'bar'
            )
        ), $NotEquals->compile());

        $NotIn = new NotIn(array('foo', array('1234')));
        $this->assertEquals(array(
            'foo' => array(
                NotIn::OPERATOR => array('1234')
            )
        ), $NotIn->compile());

        $NotNull = new NotNull(array('foo', array('1234')));
        $this->assertEquals(array(
            'foo' => array(
                NotNull::OPERATOR
            )
        ), $NotNull->compile());

        $Starts = new Starts(array('foo', 'bar'));
        $this->assertEquals(array(
            'foo' => array(
                Starts::OPERATOR => 'bar'
            )
        ), $Starts->compile());
    }
}
