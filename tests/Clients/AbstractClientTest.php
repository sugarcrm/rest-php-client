<?php
/**
 * ©[2016] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace SugarAPI\SDK\Tests\Clients;

use SugarAPI\SDK\Tests\Stubs\Client\ClientStub;

/**
 * Class AbstractClientTest
 * @package SugarAPI\SDK\Tests\Clients
 * @coversDefaultClass SugarAPI\SDK\Client\Abstracts\AbstractClient
 * @group clients
 */
class AbstractClientTest extends \PHPUnit_Framework_TestCase {

    protected static $token;

    public static function setUpBeforeClass()
    {
        $token = new \stdClass();
        $token->access_token = '1234';
        $token->expires_in = 3600;
        $token->refresh_token = '5678';
        $token->token_type = 'bearer';
        $token->refresh_expires_in = 1209600;
        $token->download_token = '101010';
        $token->scope = null;

        static::$token = $token;
    }

    public static function tearDownAfterClass()
    {
    }

    protected $server = 'localhost';
    protected $credentials = array(
        'username' => 'admin',
        'password' => 'password',
        'client_id' => 'sdk_test',
        'client_secret' => 'sdk_test_secret',
        'platform' => 'api'
    );

    public function setUp()
    {
        parent::setUp();
    }

    public function tearDown()
    {
        parent::tearDown();
    }

    /**
     * @return ClientStub $Stub
     * @covers ::__construct
     * @covers ::registerSDKEndpoints
     * @group abstractClient
     */
    public function testConstructor(){
        $Stub = new ClientStub();
        $this->assertEquals('',$Stub->getServer());
        $this->assertEquals(array(),$Stub->getCredentials());
        $this->assertEquals('http:/rest/v10/',$Stub->getAPIUrl());
        $this->assertEmpty($Stub->getToken());
        $this->assertEquals(false,$Stub->authenticated());
        $this->assertAttributeNotEmpty('entryPoints',$Stub);
        unset($Stub);

        $Stub = new ClientStub($this->server);
        $this->assertEquals($this->server,$Stub->getServer());
        $this->assertEquals(array(),$Stub->getCredentials());
        $this->assertEquals("http://".$this->server."/rest/v10/",$Stub->getAPIUrl());
        $this->assertEmpty($Stub->getToken());
        $this->assertEquals(false,$Stub->authenticated());
        $this->assertAttributeNotEmpty('entryPoints',$Stub);
        unset($Stub);

        $Stub = new ClientStub($this->server,$this->credentials);
        $this->assertEquals($this->server,$Stub->getServer());
        $this->assertEquals($this->credentials,$Stub->getCredentials());
        $this->assertEquals("http://".$this->server."/rest/v10/",$Stub->getAPIUrl());
        $this->assertEmpty($Stub->getToken());
        $this->assertEquals(false,$Stub->authenticated());
        $this->assertAttributeNotEmpty('entryPoints',$Stub);

        return $Stub;
    }

    /**
     * @param ClientStub $Stub
     * @depends testConstructor
     * @covers ::setServer
     * @covers ::getServer
     * @covers ::getAPIUrl
     * @group abstractClient
     * @return ClientStub
     */
    public function testSetServer($Stub){
        $Stub->setServer('http://localhost');
        $this->assertEquals("http://localhost",$Stub->getServer());
        $this->assertEquals("http://localhost/rest/v10/",$Stub->getAPIUrl());
        $Stub->setServer('192.168.1.20');
        $this->assertEquals('192.168.1.20',$Stub->getServer());
        $this->assertEquals("http://192.168.1.20/rest/v10/",$Stub->getAPIUrl());
        $Stub->setServer('https://tester.test.com');
        $this->assertEquals('https://tester.test.com',$Stub->getServer());
        $this->assertEquals("https://tester.test.com/rest/v10/",$Stub->getAPIUrl());
        $Stub->setServer($this->server);
        $this->assertEquals($this->server,$Stub->getServer());
        $this->assertEquals("http://".$this->server."/rest/v10/",$Stub->getAPIUrl());

        return $Stub;
    }

    /**
     * @param ClientStub $Stub
     * @depends testSetServer
     * @covers ::setCredentials
     * @covers ::getCredentials
     * @group abstractClient
     * @return ClientStub
     */
    public function testSetCredentials($Stub){
        $Stub->setCredentials(array());
        $this->assertEquals(array(),$Stub->getCredentials());
        $Stub->setCredentials($this->credentials);
        $this->assertEquals($this->credentials,$Stub->getCredentials());

        return $Stub;
    }

    /**
     * @param ClientStub $Stub
     * @depends testSetCredentials
     * @covers ::setToken
     * @covers ::getToken
     * @covers ::authenticated
     * @group abstractClient
     * @return ClientStub
     */
    public function testSetToken($Stub){

        static::$token->expires_in = 0;
        $Stub->setToken(static::$token);
        $this->assertEquals(static::$token,$Stub->getToken());
        $this->assertEquals(false,$Stub->authenticated());

        static::$token->expires_in = 3600;

        $Stub->setToken(static::$token);
        $this->assertEquals(static::$token,$Stub->getToken());
        $this->assertEquals(true,$Stub->authenticated());

        return $Stub;
    }

    /**
     * @param ClientStub $Stub
     * @depends testSetToken
     * @covers ::getToken
     * @covers ::storeToken
     * @covers ::getStoredToken
     * @covers ::setCredentials
     * @group abstractClient
     */
    public function testTokenStorage($Stub){
        $Stub->storeToken(static::$token,$this->credentials['client_id']);
        $Stub->storeToken(static::$token,'custom_client');

        unset($Stub);

        $Stub = new ClientStub($this->server,$this->credentials);
        $this->assertEquals(static::$token,$Stub->getToken());
        $this->assertEquals(static::$token,$Stub->getStoredToken($this->credentials['client_id']));
        $this->assertEquals(static::$token,$Stub->getStoredToken('custom_client'));

        $this->credentials['client_id'] = 'custom_client';
        $Stub->setCredentials($this->credentials);
        $this->assertEquals(static::$token,$Stub->getToken());
    }

    /**
     * @depends testTokenStorage
     * @covers ::removeStoredToken
     * @group abstractClients
     */
    public function testRemoveStoredToken(){
        ClientStub::removeStoredToken('custom_client');
        $this->assertEmpty(ClientStub::getStoredToken('custom_client'));

        ClientStub::removeStoredToken($this->credentials['client_id']);
        $this->assertEmpty(ClientStub::getStoredToken($this->credentials['client_id']));

    }

    /**
     * @param ClientStub
     * @depends testSetToken
     * @covers ::registerEndpoint
     * @covers ::__call
     * @group abstractClients
     * @return ClientStub
     */
    public function testRegisterEndpoint($Stub){
        $Stub->registerEndpoint('unitTest','SugarAPI\\SDK\\Tests\\Stubs\\Endpoint\\GetEndpointStub');
        $UnitTestEP = $Stub->unitTest();
        $this->assertInstanceOf('SugarAPI\\SDK\\Tests\\Stubs\\Endpoint\\GetEndpointStub',$UnitTestEP);
        return $Stub;
    }

    /**
     * @param ClientStub $Stub
     * @depends testRegisterEndpoint
     * @covers ::registerEndpoint
     * @expectedException SugarAPI\SDK\Exception\Endpoint\EndpointException
     * @expectedExceptionMessageRegExp /Class must extend SugarAPI\\SDK\\Endpoint\\Interfaces\\EPInterface/
     * @group abstractClients
     * @return ClientStub
     */
    public function testInvalidRegistration($Stub){
        $Stub->registerEndpoint('invalidEP','SugarAPI\SDK\SugarAPI');
        return $Stub;
    }

    /**
     * @param ClientStub $Stub
     * @depends testRegisterEndpoint
     * @covers ::__call
     * @expectedException SugarAPI\SDK\Exception\Endpoint\EndpointException
     * @expectedExceptionMessageRegExp /Unregistered Endpoint/
     * @group abstractClients
     * @return ClientStub
     */
    public function testUnregisteredEndpoint($Stub){
        $Stub->test();
        return $Stub;
    }

    /**
     * @covers ::login
     * @expectedException SugarAPI\SDK\Exception\Authentication\AuthenticationException
     * @expectedExceptionMessageRegExp /Login Response/
     * @group abstractClients
     * @return ClientStub
     */
    public function testLoginException(){
        $Stub = new ClientStub($this->server);
        $this->assertEquals(FALSE,$Stub->login());
        unset($Stub);

        $Stub = new ClientStub($this->server,$this->credentials);
        $Stub->login();
        return $Stub;
    }

    /**
     * @depends testLoginException
     * @covers ::refreshToken
     * @expectedException SugarAPI\SDK\Exception\Authentication\AuthenticationException
     * @expectedExceptionMessageRegExp /Refresh Response/
     * @group abstractClients
     * @return ClientStub
     */
    public function testRefreshException(){
        $Stub = new ClientStub($this->server,$this->credentials);
        $this->assertEquals(FALSE,$Stub->refreshToken());
        $Stub->setToken(static::$token);
        $Stub->refreshToken();
        return $Stub;
    }

    /**
     * @depends testRefreshException
     * @covers ::logout
     * @expectedException SugarAPI\SDK\Exception\Authentication\AuthenticationException
     * @expectedExceptionMessageRegExp /Logout Response/
     * @group abstractClients
     * @return ClientStub
     */
    public function testLogoutException(){
        $Stub = new ClientStub($this->server,$this->credentials);
        $this->assertEquals(FALSE,$Stub->logout());
        $Stub->setToken(static::$token);
        $Stub->logout();
        return $Stub;
    }


}
