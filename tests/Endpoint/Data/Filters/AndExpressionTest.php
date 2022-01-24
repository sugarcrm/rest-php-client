<?php
/**
 * User: mrussell
 * Date: 4/30/17
 * Time: 4:19 PM
 */

namespace Sugarcrm\REST\Tests\Endpoint\Data\Filters;

use Sugarcrm\REST\Endpoint\Data\Filters\Expression\AndExpression;


/**
 * Class AndExpressionTest
 * @package Sugarcrm\REST\Tests\Endpoint\Data\Filters
 * @coversDefaultClass Sugarcrm\REST\Endpoint\Data\Filters\Expression\AndExpression
 * @group AndExpressionTest
 */
class AndExpressionTest extends \PHPUnit\Framework\TestCase
{

    public static function setUpBeforeClass(): void
    {
        //Add Setup for static properties here
    }

    public static function tearDownAfterClass(): void
    {
        //Add Tear Down for static properties here
    }

    public function setUp(): void
    {
        parent::setUp();
    }

    public function tearDown(): void
    {
        parent::tearDown();
    }

    /**
     * @covers ::compile
     */
    public function testCompile()
    {
        $Expression = new AndExpression();
        $this->assertArrayHasKey(AndExpression::OPERATOR,$Expression->compile());
    }

}
