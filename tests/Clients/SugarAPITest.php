<?php
/**
 * ©[2016] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace SugarAPI\SDK\Tests\Clients;

use SugarAPI\SDK\SugarAPI;

/**
 * Class SugarAPITest
 * @package SugarAPI\SDK\Tests\Clients
 * @coversDefaultClass SugarAPI\SDK\SugarAPI
 * @group clients
 */
class SugarAPITest extends \PHPUnit_Framework_TestCase {


    public static function setUpBeforeClass()
    {
    }

    public static function tearDownAfterClass()
    {
    }

    protected $SugarAPI;
    protected $credentials = array(
        'username' => 'admin',
        'password' => 'asdf',
        'client_id' => 'sugar',
        'client_secret' => '',
        'platform' => 'api'
    );


    public function setUp()
    {
        $this->SugarAPI = new SugarAPI();
        parent::setUp();
    }

    public function tearDown()
    {
        unset($this->SugarAPI);
        parent::tearDown();
    }

    /**
     * @test
     * @covers ::setCredentials
     * @group sugarapi
     */
    public function testSetCredentials()
    {
        $this->SugarAPI->setCredentials(array('username' => 'admin'));
        $this->assertEquals(array(
            'username' => 'admin',
            'password' => '',
            'client_id' => 'sugar',
            'client_secret' => '',
            'platform' => 'api'
        ),$this->SugarAPI->getCredentials());
        $this->SugarAPI->setCredentials(array('password' => 'asdf'));
        $this->assertEquals($this->credentials,$this->SugarAPI->getCredentials());
        $this->SugarAPI->setCredentials(array());
        $this->assertEquals($this->credentials,$this->SugarAPI->getCredentials());
        $this->SugarAPI->setCredentials($this->credentials);
        $this->assertEquals($this->credentials,$this->SugarAPI->getCredentials());
        $this->SugarAPI->setCredentials(array('foo' => 'bar' ));
        $this->assertEquals($this->credentials,$this->SugarAPI->getCredentials());
    }

}
