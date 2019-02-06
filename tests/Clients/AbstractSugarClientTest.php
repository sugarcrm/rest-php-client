<?php
/**
 * ©[2016] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace SugarAPI\SDK\Tests\Clients;

use SugarAPI\SDK\Tests\Stubs\Client\SugarClientStub;

/**
 * Class AbstractSugarClientTest
 * @package SugarAPI\SDK\Tests\Clients
 * @coversDefaultClass SugarAPI\SDK\Client\Abstracts\AbstractSugarClient
 * @group clients
 */
class AbstractSugarClientTest extends \PHPUnit_Framework_TestCase {

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
        'client_id' => 'sugar_abstract_test',
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
     * @return SugarClientStub $Stub
     * @covers ::__construct
     * @covers ::registerSDKEndpoints
     * @group abstractClient
     */
    public function testConstructor(){
        $Stub = new SugarClientStub();
        $this->assertEquals('',$Stub->getServer());
        $this->assertEquals(array(
            'username' => '',
            'password' => '',
            'client_id' => '',
            'client_secret' => '',
            'platform' => ''
        ),$Stub->getCredentials());
        $this->assertEquals('http:///rest/v10/',$Stub->getAPIUrl());
        $this->assertEmpty($Stub->getToken());
        $this->assertEquals(false,$Stub->authenticated());
        $this->assertAttributeNotEmpty('entryPoints',$Stub);
        unset($Stub);

        $Stub = new SugarClientStub($this->server);
        $this->assertEquals($this->server,$Stub->getServer());
        $this->assertEquals(array(
            'username' => '',
            'password' => '',
            'client_id' => '',
            'client_secret' => '',
            'platform' => ''
        ),$Stub->getCredentials());
        $this->assertEquals("http://".$this->server."/rest/v10/",$Stub->getAPIUrl());
        $this->assertEmpty($Stub->getToken());
        $this->assertEquals(false,$Stub->authenticated());
        $this->assertAttributeNotEmpty('entryPoints',$Stub);
        unset($Stub);

        $Stub = new SugarClientStub($this->server,$this->credentials);
        $this->assertEquals($this->server,$Stub->getServer());
        $this->assertEquals($this->credentials,$Stub->getCredentials());
        $this->assertEquals("http://".$this->server."/rest/v10/",$Stub->getAPIUrl());
        $this->assertEmpty($Stub->getToken());
        $this->assertEquals(false,$Stub->authenticated());
        $this->assertAttributeNotEmpty('entryPoints',$Stub);

        return $Stub;
    }

    /**
     * @param SugarClientStub $Stub
     * @depends testConstructor
     * @covers ::setServer
     * @covers ::getServer
     * @covers ::setAPIUrl
     * @covers ::getAPIUrl
     * @group abstractClient
     * @return SugarClientStub
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
        $Stub->setServer('https://tester.test.com/SugarTest');
        $this->assertEquals('https://tester.test.com/SugarTest',$Stub->getServer());
        $this->assertEquals("https://tester.test.com/SugarTest/rest/v10/",$Stub->getAPIUrl());
        $Stub->setServer($this->server);
        $this->assertEquals($this->server,$Stub->getServer());
        $this->assertEquals("http://".$this->server."/rest/v10/",$Stub->getAPIUrl());


        return $Stub;
    }

    /**
     * @param SugarClientStub $Stub
     * @depends testConstructor
     * @covers ::setVersion
     * @covers ::getVersion
     * @covers ::getAPIUrl
     * @group abstractClient
     * @return SugarClientStub
     */
    public function testSetVersion($Stub){
        $Stub->setVersion('10');
        $this->assertEquals("http://localhost/rest/v10/",$Stub->getAPIUrl());
        $this->assertEquals(10,$Stub->getVersion());
        $Stub->setVersion(10);
        $this->assertEquals("http://localhost/rest/v10/",$Stub->getAPIUrl());
        $this->assertEquals(10,$Stub->getVersion());
        $Stub->setVersion(11);
        $this->assertEquals("http://localhost/rest/v11/",$Stub->getAPIUrl());
        $this->assertEquals(11,$Stub->getVersion());
        $Stub->setVersion('11_4');
        $this->assertEquals("http://localhost/rest/v11_4/",$Stub->getAPIUrl());
        $this->assertEquals('11_4',$Stub->getVersion());
        return $Stub;
    }

    /**
     * @param SugarClientStub $Stub
     * @depends testSetServer
     * @covers ::setCredentials
     * @covers ::getCredentials
     * @group abstractClient
     * @return SugarClientStub
     */
    public function testSetCredentials($Stub){
        $Stub->setCredentials(array('username' => 'admin'));
        $this->assertEquals($this->credentials,$Stub->getCredentials());
        $Stub->setCredentials(array('password' => 'asdf'));
        $newCreds = $this->credentials;
        $newCreds['password'] = 'asdf';
        $this->assertEquals($newCreds,$Stub->getCredentials());
        $Stub->setCredentials(array());
        $this->assertEquals($newCreds,$Stub->getCredentials());
        $Stub->setCredentials($this->credentials);
        $this->assertEquals($this->credentials,$Stub->getCredentials());
        $Stub->setCredentials(array('foo' => 'bar' ));
        $this->assertEquals($this->credentials,$Stub->getCredentials());
        $newCreds['username'] = 'test';
        $Stub->setCredentials($newCreds);
        $this->assertEquals($newCreds,$Stub->getCredentials());

        return $Stub;
    }

    /**
     * @param SugarClientStub $Stub
     * @depends testSetCredentials
     * @covers ::setToken
     * @covers ::getToken
     * @covers ::authenticated
     * @covers ::expiredToken
     * @group abstractClient
     * @return SugarClientStub
     */
    public function testSetToken($Stub){
        static::$token->expires_in = 0;

        $Stub->setToken(static::$token);
        $this->assertEquals(static::$token,$Stub->getToken());
        $this->assertEquals(false,$Stub->authenticated());

        static::$token->expires_in = 3600;
        unset(static::$token->expiration);

        $Stub->setToken(static::$token);
        $this->assertEquals(static::$token,$Stub->getToken());
        $this->assertEquals(true,$Stub->authenticated());

        return $Stub;
    }

    /**
     * @param SugarClientStub $Stub
     * @depends testSetToken
     * @expectedException SugarAPI\SDK\Exception\SDKException
     * @expectedExceptionMessageRegExp /Sugar API Client requires Token to be of type \\stdClass/
     * @covers ::setToken
     * @group abstractClient
     */
    public function testSetTokenException($Stub){
        $Stub->setToken(array());
    }

    /**
     * @depends testSetToken
     * @covers ::getToken
     * @covers ::storeToken
     * @covers ::removeStoredToken
     * @covers ::getStoredToken
     * @covers ::setCredentials
     * @group abstractClient
     */
    public function testTokenStorage(){
        $Stub = new SugarClientStub($this->server,$this->credentials);
        $this->assertEmpty($Stub->getToken());
        $Stub->storeToken(static::$token,$Stub->getCredentials());
        $this->assertEmpty($Stub->getToken());

        unset($Stub);

        $Stub = new SugarClientStub($this->server,$this->credentials);
        $this->assertEquals($Stub->getToken(),static::$token);

        $creds = $this->credentials;
        $creds['platform'] = 'base';

        $Stub2 = new SugarClientStub($this->server,$creds);
        $this->assertEmpty($Stub2->getToken());
        $Stub2->storeToken(static::$token,$Stub2->getCredentials());
        $this->assertEmpty($Stub2->getToken());

        $creds['username'] = 'tokenStorage';
        $Stub3 = new SugarClientStub($this->server,$creds);
        $this->assertEmpty($Stub3->getToken());
        $Stub3->storeToken(static::$token,$Stub3->getCredentials());
        $this->assertEmpty($Stub3->getToken());

        unset($Stub2);

        $Stub2 = new SugarClientStub($this->server,$creds);
        $this->assertEquals(static::$token,$Stub2->getToken());

        unset($Stub3);

        $creds['username'] = 'admin';
        $Stub3 = new SugarClientStub($this->server,$creds);
        $this->assertEquals(static::$token,$Stub3->getToken());

        unset($Stub2);
        unset($Stub);
        unset($Stub3);

        $token = SugarClientStub::getStoredToken($this->credentials);
        $this->assertEquals($token,static::$token);

        SugarClientStub::removeStoredToken($this->credentials);
        $token = SugarClientStub::getStoredToken($creds);
        $this->assertEquals($token,static::$token);

        $creds['username'] = 'tokenStorage';
        SugarClientStub::removeStoredToken($creds);
        $token = SugarClientStub::getStoredToken($creds);
        $this->assertEmpty($token);

        SugarClientStub::removeStoredToken(array('client_id' => 'sugar_abstract_test'));
        $token = SugarClientStub::getStoredToken($this->credentials);
        $this->assertEmpty($token);

        $this->assertEquals(FALSE,SugarClientStub::storeToken(static::$token,'test'));
        $this->assertEquals(FALSE,SugarClientStub::storeToken(static::$token,array('client_id' => 'test')));
        $this->assertEquals(FALSE,SugarClientStub::storeToken(static::$token,array('client_id' => 'test','platform' => 'test')));
        $this->assertEquals(FALSE,SugarClientStub::storeToken(static::$token,array('client_id' => 'test','username' => 'test')));
        $this->assertEquals(FALSE,SugarClientStub::removeStoredToken('test'));
        $this->assertEquals(FALSE,SugarClientStub::removeStoredToken(array('platform' => 'test')));
    }

    /**
     * @param SugarClientStub
     * @depends testSetToken
     * @covers ::__call
     * @group abstractClients
     * @return SugarClientStub
     */
    public function testCall($Stub){
        $Stub->registerEndpoint('unitTest','SugarAPI\\SDK\\Tests\\Stubs\\Endpoint\\GetEndpointStub');
        $UnitTestEP = $Stub->unitTest();
        $this->assertInstanceOf('SugarAPI\\SDK\\Tests\\Stubs\\Endpoint\\GetEndpointStub',$UnitTestEP);
        $this->assertAttributeNotEmpty('accessToken',$UnitTestEP);
        $this->assertAttributeEquals(static::$token->access_token,'accessToken',$UnitTestEP);
        return $Stub;
    }

    /**
     * @covers ::login
     * @group abstractClients
     * @return SugarClientStub
     */
    public function testLogin(){
        $Stub = new SugarClientStub($this->server);
        $this->assertEquals(FALSE,$Stub->login());
        return $Stub;
    }

    /**
     * @param SugarClientStub $Stub
     * @depends testLogin
     * @covers ::login
     * @expectedException SugarAPI\SDK\Exception\Authentication\AuthenticationException
     * @expectedExceptionMessageRegExp /Login Response/
     * @group abstractClients
     */
    public function testLoginException($Stub){
        $Stub->setCredentials($this->credentials);
        $Stub->login();
    }

    /**
     * @param SugarClientStub $Stub
     * @depends testLogin
     * @covers ::login
     * @expectedException SugarAPI\SDK\Exception\Authentication\AuthenticationException
     * @expectedExceptionMessageRegExp /Login Response/
     * @group abstractClients
     */
    public function testLoginExceptionCurlError($Stub){
        $Stub->setCredentials($this->credentials);
        $Stub->setServer('test.foo.bar');
        $Stub->login();
    }

    /**
     * @covers ::refreshToken
     * @group abstractClients
     * @return SugarClientStub
     */
    public function testRefreshToken(){
        $Stub = new SugarClientStub($this->server);
        $this->assertEquals(FALSE,$Stub->refreshToken());
        return $Stub;
    }

    /**
     * @param SugarClientStub $Stub
     * @depends testRefreshToken
     * @covers ::refreshToken
     * @expectedException SugarAPI\SDK\Exception\Authentication\AuthenticationException
     * @expectedExceptionMessageRegExp /Refresh Response/
     * @group abstractClients
     */
    public function testRefreshException($Stub){
        $Stub->setCredentials($this->credentials);
        $Stub->setToken(static::$token);
        $Stub->refreshToken();
    }

    /**
     * @param SugarClientStub $Stub
     * @depends testRefreshToken
     * @covers ::refreshToken
     * @expectedException SugarAPI\SDK\Exception\Authentication\AuthenticationException
     * @expectedExceptionMessageRegExp /Refresh Response/
     * @group abstractClients
     */
    public function testRefreshExceptionCurlError($Stub){
        $Stub->setCredentials($this->credentials);
        $Stub->setToken(static::$token);
        $Stub->setServer('test.foo.bar');
        $Stub->refreshToken();
    }

    /**
     * @covers ::logout
     * @group abstractClients
     * @return SugarClientStub
     */
    public function testLogout(){
        $Stub = new SugarClientStub($this->server);
        $this->assertEquals(FALSE,$Stub->logout());
        return $Stub;
    }

    /**
     * @param SugarClientStub $Stub
     * @depends testLogout
     * @covers ::logout
     * @expectedException SugarAPI\SDK\Exception\Authentication\AuthenticationException
     * @expectedExceptionMessageRegExp /Logout Response/
     * @group abstractClients
     */
    public function testLogoutException($Stub){
        $Stub->setToken(static::$token);
        $Stub->logout();
    }

    /**
     * @param SugarClientStub $Stub
     * @depends testLogout
     * @covers ::logout
     * @expectedException SugarAPI\SDK\Exception\Authentication\AuthenticationException
     * @expectedExceptionMessageRegExp /Logout Response/
     * @group abstractClients
     */
    public function testLogoutExceptionCurlError($Stub){
        $Stub->setToken(static::$token);
        $Stub->setServer('test.foo.bar');
        $Stub->logout();
    }


}
