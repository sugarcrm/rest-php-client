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
     * @expectedException Sugarcrm\REST\Exception\Filter\UnknownFilterOperator
     */
    public function testUnknownFilterOperatorException(){
        $Expression = new AndExpression();
        $Expression->foo();
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
    public function testCall(){

    }

}
