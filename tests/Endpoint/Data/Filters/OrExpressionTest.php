<?php
/**
 * User: mrussell
 * Date: 4/30/17
 * Time: 4:21 PM
 */

namespace Sugarcrm\REST\Tests\Endpoint\Data\Filters;

use Sugarcrm\REST\Endpoint\Data\Filters\Expression\OrExpression;


/**
 * Class OrExpressionTest
 * @package Sugarcrm\REST\Tests\Endpoint\Data\Filters
 * @coversDefaultClass Sugarcrm\REST\Endpoint\Data\Filters\Expression\OrExpression
 * @group OrExpressionTest
 */
class OrExpressionTest extends \PHPUnit\Framework\TestCase
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
     * @covers ::compile
     */
    public function testCompile()
    {
        $Expression = new OrExpression();
        $this->assertArrayHasKey(OrExpression::OPERATOR,$Expression->compile());
    }

}
