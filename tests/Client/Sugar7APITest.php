<?php
/**
 * ©[2017] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace Sugarcrm\REST\Tests\Client;

use Sugarcrm\REST\Auth\SugarOAuthController;
use Sugarcrm\REST\Client\Sugar7API;
use Sugarcrm\REST\Tests\Stubs\Auth\SugarOAuthStub;


/**
 * Class Sugar7APITest
 * @package Sugarcrm\REST\Tests\Client
 * @coversDefaultClass Sugarcrm\REST\Client\Sugar7Api
 * @group Sugar7APITest
 */
class Sugar7APITest extends \PHPUnit_Framework_TestCase
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
     * @covers ::__construct
     * @covers ::init
     * @covers ::setAPIUrl
     */
    public function testConstructor(){
        $Client = new Sugar7API();
        $this->assertNotEmpty($Client->getAuth());
        $this->assertNotEmpty($Client->getEndpointProvider());
        $this->assertEquals(10,$Client->getVersion());
        $this->assertEmpty($Client->getServer());
        $this->assertEmpty($Client->getAPIUrl());
        $Client = new Sugar7API('localhost');
        $this->assertNotEmpty($Client->getAuth());
        $this->assertNotEmpty($Client->getEndpointProvider());
        $this->assertEquals(10,$Client->getVersion());
        $this->assertEquals('localhost',$Client->getServer());
        $this->assertEquals('http://localhost/rest/v10/',$Client->getAPIUrl());
        $Client = new Sugar7API('localhost',array(
                                                'username' => 'admin',
                                                'password' => 'asdf'
                                            )
        );
        $this->assertNotEmpty($Client->getAuth());
        $this->assertEquals(array(
            'username' => 'admin',
            'password' => 'asdf',
            'client_id' => 'sugar',
            'client_secret' => '',
            'platform' => 'api'
        ),$Client->getAuth()->getCredentials());
        $this->assertNotEmpty($Client->getEndpointProvider());
        $this->assertEquals(10,$Client->getVersion());
        $this->assertEquals('localhost',$Client->getServer());
        $this->assertEquals('http://localhost/rest/v10/',$Client->getAPIUrl());
    }

    /**
     * @covers ::login
     */
    public function testLogin(){
        $Client = new Sugar7API('localhost');
        $Auth = new SugarOAuthStub();
        $Client->setAuth($Auth);
        $this->assertEquals(true,$Client->login('admin','asdf'));
        $this->assertEquals(array(
            'username' => 'admin',
            'password' => 'asdf',
            'client_id' => 'sugar',
            'client_secret' => '',
            'platform' => 'api'
        ),$Client->getAuth()->getCredentials());
        $this->assertEquals(true,$Client->login('user1','asdf'));
        $this->assertEquals(array(
            'username' => 'user1',
            'password' => 'asdf',
            'client_id' => 'sugar',
            'client_secret' => '',
            'platform' => 'api'
        ),$Client->getAuth()->getCredentials());
        $this->assertEquals(true,$Client->login(NULL,'abc123'));
        $this->assertEquals(array(
            'username' => 'user1',
            'password' => 'abc123',
            'client_id' => 'sugar',
            'client_secret' => '',
            'platform' => 'api'
        ),$Client->getAuth()->getCredentials());
        $this->assertEquals(true,$Client->login());
        $this->assertEquals(array(
            'username' => 'user1',
            'password' => 'abc123',
            'client_id' => 'sugar',
            'client_secret' => '',
            'platform' => 'api'
        ),$Client->getAuth()->getCredentials());
    }

    /**
     * @covers ::refreshToken
     */
    public function testRefreshToken(){
        $Client = new Sugar7API('localhost');
        $Auth = new SugarOAuthStub();
        $Auth->setCredentials(array(
            'username' => '',
            'password' => '',
            'client_id' => 'sugar',
            'platform' => 'api'
        ));
        $Client->setAuth($Auth);
        $this->assertEquals(false,$Client->refreshToken());
        $Auth->setCredentials(array(
            'username' => '',
            'password' => '',
            'client_id' => 'sugar',
            'client_secret' => '',
            'platform' => 'api'
        ));
        $this->assertEquals(true,$Client->refreshToken());
    }

    /**
     * @covers ::logout
     */
    public function testLogout(){
        $Client = new Sugar7API('localhost');
        $Auth = new SugarOAuthStub();
        $Client->setAuth($Auth);
        $this->assertEquals(true,$Client->logout());
    }

    /**
     * @covers ::sudo
     */
    public function testSudo()
    {
        $Client = new Sugar7API('localhost');
        $Auth = new SugarOAuthStub();
        $Client->setAuth($Auth);
        $this->assertEquals(false,$Client->sudo('max'));
    }
}
