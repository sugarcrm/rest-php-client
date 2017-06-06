<?php

namespace Sugarcrm\REST\Tests\Auth;

use Sugarcrm\REST\Auth\SugarOAuthController;


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
