<?php
/**
 * User: mrussell
 * Date: 4/30/17
 * Time: 5:58 PM
 */

namespace Sugarcrm\REST\Tests\Endpoint\Data\Filters;

use Sugarcrm\REST\Endpoint\Data\Filters\Operator\IsNull;


/**
 * Class IsNullTest
 * @package Sugarcrm\REST\Tests\Endpoint\Data\Filters
 * @coversDefaultClass Sugarcrm\REST\Endpoint\Data\Filters\Operator\IsNull
 * @group IsNullTest
 */
class IsNullTest extends \PHPUnit\Framework\TestCase
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
     * @covers ::setvalue
     */
    public function testSetValue(){
        $IsNull = new IsNull();
        $this->assertEmpty($IsNull->getValue());
        $this->assertEquals($IsNull,$IsNull->setValue('foo'));
        $this->assertEquals(NULL,$IsNull->getValue());
    }
}
