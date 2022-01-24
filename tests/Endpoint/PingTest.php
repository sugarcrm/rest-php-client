<?php
/**
 * ©[2019] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace Sugarcrm\REST\Tests\Endpoint;

use Sugarcrm\REST\Endpoint\Ping;
use Sugarcrm\REST\Tests\Stubs\Auth\SugarOAuthStub;


/**
 * Class PingTest
 * @package Sugarcrm\REST\Tests\Endpoint
 * @coversDefaultClass Sugarcrm\REST\Endpoint\Ping
 * @group PingTest
 */
class PingTest extends \PHPUnit\Framework\TestCase
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
     * @covers ::whattimeisit
     */
    public function testWhattimeisit()
    {
        $Ping = new Ping();
        $Ping->setBaseUrl('http://localhost/rest/v10');
        $Ping->whattimeisit();
        $this->assertEquals('http://localhost/rest/v10/ping/whattimeisit',$Ping->getRequest()->getURL());
        $this->assertEquals(array(),$Ping->getOptions());
    }

}
