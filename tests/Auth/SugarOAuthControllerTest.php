<?php
/**
 * ©[2022] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace Sugarcrm\REST\Tests\Auth;

use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use MRussell\REST\Tests\Stubs\Endpoint\AuthEndpoint;
use Psr\Log\Test\TestLogger;
use Sugarcrm\REST\Endpoint\OAuth2Sudo;
use Sugarcrm\REST\Endpoint\OAuth2Token;
use Sugarcrm\REST\Tests\Stubs\Auth\SugarOAuthStub;
use Sugarcrm\REST\Tests\Stubs\Client\Client;

/**
 * Class SugarOAuthControllerTest
 * @package Sugarcrm\REST\Tests\Auth
 * @coversDefaultClass \Sugarcrm\REST\Auth\SugarOAuthController
 * @group SugarOAuthControllerTest
 */
class SugarOAuthControllerTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var Client
     */
    protected static $client;

    public static function setUpBeforeClass(): void
    {
        //Add Setup for static properties here
        self::$client = new Client();
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
        $this->assertEquals(true, in_array('sudo', $Auth->getActions()));
    }

    /**
     * @covers ::getAuthHeaderValue
     */
    public function testAuthHeader()
    {
        $Auth = new SugarOAuthStub();
        $Request = $Auth->configureRequest(new Request("POST", "/"));
        $headers = $Request->getHeaders();
        $this->assertEquals(['bar'], $headers['OAuth-Token']);
    }

    /**
     * @covers ::getCacheKey
     * @covers ::generateUniqueCacheString
     */
    public function testCacheKey()
    {
        $Auth = new SugarOAuthStub();
        $Logger = new TestLogger();
        $Auth->setLogger($Logger);
        $Reflected = new \ReflectionClass($Auth);
        $generateUniqueCacheString = $Reflected->getMethod('generateUniqueCacheString');
        $generateUniqueCacheString->setAccessible(true);

        $this->assertEquals("client_id_base_username", $generateUniqueCacheString->invoke($Auth, [
            'client_id' => 'client_id',
            'client_secret' => 'client_secret',
            'password' => 'password',
            'username' => 'username',
            'platform' => 'base'
        ]));
        $this->assertTrue($Logger->hasInfoThatContains("Cannot use server in cache string."));
        $Logger->reset();
        $LoginEP = new OAuth2Token();
        $LoginEP->setBaseUrl("http://localhost/api");
        $Auth->setActionEndpoint($Auth::ACTION_AUTH, $LoginEP);
        $this->assertEquals("http://localhost/api_client_id_base_username", $generateUniqueCacheString->invoke($Auth, [
            'client_id' => 'client_id',
            'client_secret' => 'client_secret',
            'password' => 'password',
            'username' => 'username',
            'platform' => 'base'
        ]));
        $this->assertFalse($Logger->hasInfoThatContains("Cannot use server in cache string."));


        $LoginEP = new OAuth2Token();
        $LoginEP->setClient(self::$client);
        $Auth->setActionEndpoint($Auth::ACTION_AUTH, $LoginEP);
        $this->assertEquals("http://phpunit.tests_client_id_base_username_sudofoobar", $generateUniqueCacheString->invoke($Auth, [
            'client_id' => 'client_id',
            'client_secret' => 'client_secret',
            'password' => 'password',
            'username' => 'username',
            'platform' => 'base',
            'sudo' => 'foobar'
        ]));
        $this->assertFalse($Logger->hasInfoThatContains("Cannot use server in cache string."));

        $Auth->setCredentials([
            'client_id' => 'client_id',
            'client_secret' => 'client_secret',
            'password' => 'password',
            'username' => 'username',
            'platform' => 'base',
            'sudo' => 'foobar'
        ]);
        $this->assertEquals(sha1("http://phpunit.tests_client_id_base_username_sudofoobar"), $Auth->getCacheKey());
    }

    /**
     * @covers ::sudo
     * @covers ::configureSudoEndpoint
     * @covers \Sugarcrm\REST\Client\SugarApi::sudo
     */
    public function testSudo()
    {
        self::$client->container = [];
        self::$client->mockResponses->append(new Response(200, [], json_encode(['access_token' => 'at-bar'])));
        self::$client->mockResponses->append(new Response(500, []));

        $Auth = new SugarOAuthStub();
        $logger = new TestLogger();
        $Auth->setLogger($logger);
        $Auth->setCredentials(array(
            'username' => 'system',
            'password' => 'asdf',
            'client_id' => 'sugar',
            'client_secret' => '',
            'platform' => 'api'
        ));
        $EP = new OAuth2Sudo();
        $EP->setClient(self::$client);
        $EP->setBaseUrl('http://localhost/rest/v11');
        $Auth->setActionEndpoint($Auth::ACTION_SUGAR_SUDO, $EP);
        $this->assertEquals(true, $Auth->sudo('max'));
        $request = current(self::$client->container)['request'];
        $this->assertEquals('http://localhost/rest/v11/oauth2/sudo/max', $request->getUri()->__toString());
        $this->assertEquals('{"platform":"api","client_id":"sugar"}', $request->getBody()->getContents());
        $this->assertEquals(false, $Auth->sudo('max'));
        $this->assertEquals(true, $logger->hasErrorThatContains("Exception Occurred sending SUDO request:"));
    }
}
