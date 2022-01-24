<?php

namespace Sugarcrm\REST\Tests\Auth;


use Sugarcrm\REST\Auth\SugarOAuthController;
use Sugarcrm\REST\Endpoint\OAuth2Sudo;
use Sugarcrm\REST\Storage\SugarStaticStorage;
use Sugarcrm\REST\Tests\Stubs\Auth\SugarOAuthStub;


/**
 * Class SugarOAuthControllerTest
 * @package Sugarcrm\REST\Tests\Auth
 * @coversDefaultClass Sugarcrm\REST\Auth\SugarOAuthController
 * @group SugarOAuthControllerTest
 */
class SugarOAuthControllerTest extends \PHPUnit\Framework\TestCase
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
     * @covers ::__construct
     */
    public function testConstructor()
    {
        $Auth = new SugarOAuthStub();
        $this->assertEquals(true,in_array('sudo',$Auth->getActions()));
    }

    /**
     * @covers ::setCredentials
     * @covers ::setPlatform
     * @covers ::getPlatform
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
        $this->assertEquals('api',$Auth->getPlatform());
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
        $this->assertEquals($Auth,$Auth->setPlatform('mobile'));
        $creds = $Auth->getCredentials();
        $this->assertEquals('mobile',$creds[$Auth::OAUTH_PROP_PLATFORM]);
        $this->assertEquals(array(
            'username' => 'admin',
            'password' => '',
            'client_id' => 'sugar',
            'client_secret' => '',
            'platform' => 'mobile'
        ),$creds);
        $this->assertEquals('mobile',$Auth->getPlatform());
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
            'platform' => 'base'
        ),$Auth->getCredentials());
        $this->assertEquals($Auth,$Auth->updateCredentials(array(
            'username' => 'admin'
        )));
        $this->assertEquals(array(
            'username' => 'admin',
            'password' => '',
            'client_id' => 'sugar',
            'client_secret' => '',
            'platform' => 'base'
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
            'platform' => 'base'
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

    /**
     * @covers ::getAuthHeaderValue
     */
    public function testAuthHeader()
    {
        $Auth = new SugarOAuthStub();
        $Request = new JSON();
        $this->assertEquals($Auth,$Auth->configureRequest($Request));
        $headers = $Request->getHeaders();
        $this->assertEquals('bar',$headers['OAuth-Token']);
    }

    /**
     * @covers ::sudo
     * @covers ::configureSudoEndpoint
     * @covers Sugarcrm\REST\Client\Sugar7API::sudo
     */
    public function testSudo()
    {
        $Auth = new SugarOAuthStub();
        $Auth->setCredentials(array(
            'username' => 'system',
            'password' => 'asdf',
            'client_id' => 'sugar',
            'client_secret' => '',
            'platform' => 'api'
        ));
        $EP = new OAuth2Sudo();
        $EP->setBaseUrl('http://localhost/rest/v10');
        $Auth->setActionEndpoint($Auth::ACTION_SUGAR_SUDO,$EP);
        $Auth->sudo('max');
        $request = $EP->getRequest();
        $this->assertEquals('http://localhost/rest/v10/oauth2/sudo/max',$request->getURL());
        $this->assertEquals(array(
            'client_id' => 'sugar',
            'platform' => 'api'
        ),$request->getBody());
    }
}
