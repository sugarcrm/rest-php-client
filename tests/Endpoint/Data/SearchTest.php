<?php
/**
 * ©[2019] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace Sugarcrm\REST\Tests\Endpoint\Data;

use Sugarcrm\REST\Endpoint\Search;


/**
 * Class SearchTest
 * @package Sugarcrm\REST\Tests\Endpoint\Data
 * @coversDefaultClass Sugarcrm\REST\Endpoint\Search
 * @group SearchTest
 */
class SearchTest extends \PHPUnit\Framework\TestCase
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

    public function testGet(){
        $Search = new Search();
        $Search['12345'] = array(
            'foo' => 'bar',
            '_module' => 'Accounts'
        );
        $Model = $Search->get('12345');
        $this->assertEquals('Accounts',$Model->getModule());
        $Search['12345'] = array(
            'foo' => 'bar'
        );
        $Model = $Search->get('12345');
        $this->assertEmpty($Model->getModule());
    }
}
