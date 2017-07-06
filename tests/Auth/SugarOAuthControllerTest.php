<?php

namespace Sugarcrm\REST\Tests\Auth;

use Sugarcrm\REST\Auth\SugarOAuthController;
use Sugarcrm\REST\Storage\SugarStaticStorage;


/**
 * Class SugarOAuthControllerTest
 * @package Sugarcrm\REST\Tests\Auth
 * @coversDefaultClass Sugarcrm\REST\Auth\SugarOAuthController
 * @group SugarOAuthControllerTest
 */
class SugarOAuthControllerTest extends \PHPUnit_Framework_TestCase
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
     * @covers ::setCredentials
     */
    public function testSetCredentials(){
        $Auth = new SugarOAuthController();
        $Storage = new SugarStaticStorage();
        $Auth->setStorageController($Storage);
        $this->assertEquals($Auth,$Auth->setCredentials(array(
            'username' => 'admin',
            'password' => '',
            'client_id' => 'sugar',
            'client_secret' => '',
            'platform' => 'api'
        )));
        $this->assertEmpty($Auth->getToken());
        $Storage->store($Auth->getCredentials(),array(
            'access_token' => '1234',
            'refresh_token' => '5678',
        ));
        $this->assertEquals($Auth,$Auth->setCredentials(array(
            'username' => 'admin',
            'password' => '',
            'client_id' => 'sugar',
            'client_secret' => '',
            'platform' => 'api'
        )));
        $this->assertEquals(array(
            'access_token' => '1234',
            'refresh_token' => '5678',
        ),$Auth->getToken());
    }

    /**
     * @covers ::updateCredentials
     */
    public function testUpdateCredentials(){
        $Auth = new SugarOAuthController();
        $this->assertEquals(array(
            'username' => '',
            'password' => '',
            'client_id' => 'sugar',
            'client_secret' => '',
            'platform' => 'api'
        ),$Auth->getCredentials());
        $this->assertEquals($Auth,$Auth->updateCredentials(array(
            'username' => 'admin'
        )));
        $this->assertEquals(array(
            'username' => 'admin',
            'password' => '',
            'client_id' => 'sugar',
            'client_secret' => '',
            'platform' => 'api'
        ),$Auth->getCredentials());
        $this->assertEquals($Auth,$Auth->updateCredentials(array(
            'username' => 'system',
            'password' => 'asdf'
        )));
        $this->assertEquals(array(
            'username' => 'system',
            'password' => 'asdf',
            'client_id' => 'sugar',
            'client_secret' => '',
            'platform' => 'api'
        ),$Auth->getCredentials());
        $this->assertEquals($Auth,$Auth->updateCredentials(array(
            'platform' => array()
        )));
        $this->assertEquals(array(
            'username' => 'system',
            'password' => 'asdf',
            'client_id' => 'sugar',
            'client_secret' => '',
            'platform' => array()
        ),$Auth->getCredentials());
    }
}
