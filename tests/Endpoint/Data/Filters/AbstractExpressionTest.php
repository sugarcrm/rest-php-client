<?php
/**
 * User: mrussell
 * Date: 4/30/17
 * Time: 4:11 PM
 */

namespace Sugarcrm\REST\Tests\Endpoint\Data\Filters;

use Sugarcrm\REST\Endpoint\Data\Filters\Expression\AndExpression;
use Sugarcrm\REST\Endpoint\Data\Filters\Expression\OrExpression;


/**
 * Class AbstractExpressionTest
 * @package Sugarcrm\REST\Tests\Endpoint\Data\Filters
 * @coversDefaultClass Sugarcrm\REST\Endpoint\Data\Filters\Expression\AbstractExpression
 * @group AbstractExpressionTest
 */
class AbstractExpressionTest extends \PHPUnit_Framework_TestCase
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
     * @covers ::getParentExpression
     * @covers ::setParentExpression
     */
    public function testGetParentExpression(){
        $And = new AndExpression();
        $Expression = new OrExpression();
        $this->assertEquals($Expression,$Expression->setParentExpression($And));
        $this->assertEquals($And,$Expression->getParentExpression());
    }

    /**
     * @covers ::__call
     */
    public function testCall()
    {
        $Expression = new AndExpression();
        $this->assertEquals($Expression,$Expression->equals('foo','bar'));
        $this->assertEquals($Expression,$Expression->notEquals('foo','bar'));
        $this->assertEquals($Expression,$Expression->starts('foo','bar'));
        $this->assertEquals($Expression,$Expression->ends('foo','bar'));
        $this->assertEquals($Expression,$Expression->contains('foo','bar'));
        $this->assertEquals($Expression,$Expression->in('foo',array('bar')));
        $this->assertEquals($Expression,$Expression->notIn('foo',array("bar")));
        $this->assertEquals($Expression,$Expression->isNull('foo'));
        $this->assertEquals($Expression,$Expression->notNull('foo'));
        $this->assertEquals($Expression,$Expression->lt('foo','bar'));
        $this->assertEquals($Expression,$Expression->lessThan('foo','bar'));
        $this->assertEquals($Expression,$Expression->lte('foo','bar'));
        $this->assertEquals($Expression,$Expression->lessThanOrEqualTo('foo','bar'));
        $this->assertEquals($Expression,$Expression->lessThanOrEquals('foo','bar'));
        $this->assertEquals($Expression,$Expression->gte('foo','bar'));
        $this->assertEquals($Expression,$Expression->greaterThanOrEqualTo('foo','bar'));
        $this->assertEquals($Expression,$Expression->greaterThanOrEquals('foo','bar'));
        $this->assertEquals($Expression,$Expression->between('foo','bar'));
        $this->assertEquals($Expression,$Expression->dateBetween('foo','bar'));
        $this->assertInstanceOf("Sugarcrm\REST\Endpoint\Data\Filters\Expression\AndExpression",$Expression->and());
        $this->assertInstanceOf("Sugarcrm\REST\Endpoint\Data\Filters\Expression\OrExpression",$Expression->or());
        $this->assertInstanceOf("Sugarcrm\REST\Endpoint\Data\Filters\Expression\DateExpression",$Expression->date('test'));

        $Expression = new OrExpression();
        $this->assertEquals($Expression,$Expression->equals('foo','bar'));
        $this->assertEquals($Expression,$Expression->notEquals('foo','bar'));
        $this->assertEquals($Expression,$Expression->starts('foo','bar'));
        $this->assertEquals($Expression,$Expression->ends('foo','bar'));
        $this->assertEquals($Expression,$Expression->contains('foo','bar'));
        $this->assertEquals($Expression,$Expression->in('foo',array('bar')));
        $this->assertEquals($Expression,$Expression->notIn('foo',array("bar")));
        $this->assertEquals($Expression,$Expression->isNull('foo'));
        $this->assertEquals($Expression,$Expression->notNull('foo'));
        $this->assertEquals($Expression,$Expression->lt('foo','bar'));
        $this->assertEquals($Expression,$Expression->lessThan('foo','bar'));
        $this->assertEquals($Expression,$Expression->lte('foo','bar'));
        $this->assertEquals($Expression,$Expression->lessThanOrEqualTo('foo','bar'));
        $this->assertEquals($Expression,$Expression->lessThanOrEquals('foo','bar'));
        $this->assertEquals($Expression,$Expression->gte('foo','bar'));
        $this->assertEquals($Expression,$Expression->greaterThanOrEqualTo('foo','bar'));
        $this->assertEquals($Expression,$Expression->greaterThanOrEquals('foo','bar'));
        $this->assertEquals($Expression,$Expression->between('foo','bar'));
        $this->assertEquals($Expression,$Expression->dateBetween('foo','bar'));
        $this->assertInstanceOf("Sugarcrm\REST\Endpoint\Data\Filters\Expression\AndExpression",$Expression->and());
        $this->assertInstanceOf("Sugarcrm\REST\Endpoint\Data\Filters\Expression\OrExpression",$Expression->or());
        $this->assertInstanceOf("Sugarcrm\REST\Endpoint\Data\Filters\Expression\DateExpression",$Expression->date('test'));
    }

    /**
     * @covers ::__call
     * @expectedException Sugarcrm\REST\Exception\Filter\UnknownFilterOperator
     */
    public function testUnknownFilterOperatorException(){
        $Expression = new AndExpression();
        $Expression->foo();
    }

}
