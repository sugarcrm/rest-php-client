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
        $token = array(
            'token' => 12345
        );

        static::$token = $token;
    }

    public static function tearDownAfterClass()
    {
    }

    protected $server = 'localhost';
    protected $credentials = array(
        'username' => 'admin',
        'password' => 'password',
        'client_id' => 'abstract_test',
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
     * @covers ::setServer
     * @covers ::getServer
     * @covers ::getAPIUrl
     * @group abstractClient
     * @return ClientStub
     */
    public function testSetServer(){
        $Stub = new ClientStub();
        $Stub->setServer('http://localhost');
        $this->assertEquals("http://localhost",$Stub->getServer());
        $this->assertEquals("http://localhost",$Stub->getAPIUrl());
        $Stub->setServer('192.168.1.20');
        $this->assertEquals('192.168.1.20',$Stub->getServer());
        $this->assertEquals("192.168.1.20",$Stub->getAPIUrl());
        $Stub->setServer('https://tester.test.com');
        $this->assertEquals('https://tester.test.com',$Stub->getServer());
        $this->assertEquals("https://tester.test.com",$Stub->getAPIUrl());
        $Stub->setServer($this->server);
        $this->assertEquals($this->server,$Stub->getServer());
        $this->assertEquals($this->server,$Stub->getAPIUrl());

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

        $Stub->setToken(array());
        $this->assertEquals(array(),$Stub->getToken());
        $this->assertEquals(FALSE,$Stub->authenticated());

        $Stub->setToken('');
        $this->assertEquals('',$Stub->getToken());
        $this->assertEquals(FALSE,$Stub->authenticated());

        $Stub->setToken(null);
        $this->assertEquals(null,$Stub->getToken());
        $this->assertEquals(FALSE,$Stub->authenticated());

        $Stub->setToken(0);
        $this->assertEquals(0,$Stub->getToken());
        $this->assertEquals(FALSE,$Stub->authenticated());

        $Stub->setToken('test');
        $this->assertEquals('test',$Stub->getToken());
        $this->assertEquals(TRUE,$Stub->authenticated());

        $Stub->setToken(new \stdClass());
        $this->assertEquals(new \stdClass(),$Stub->getToken());
        $this->assertEquals(TRUE,$Stub->authenticated());

        $Stub->setToken(array('test'));
        $this->assertEquals(array('test'),$Stub->getToken());
        $this->assertEquals(TRUE,$Stub->authenticated());

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
        $Stub->storeToken(static::$token,'test_client');

        unset($Stub);

        $Stub = new ClientStub();
        $this->assertEquals(static::$token,$Stub->getStoredToken($this->credentials['client_id']));
        $this->assertEquals(static::$token,$Stub->getStoredToken('test_client'));
    }

    /**
     * @depends testTokenStorage
     * @covers ::removeStoredToken
     * @group abstractClients
     */
    public function testRemoveStoredToken(){
        ClientStub::removeStoredToken('test_client');
        $this->assertEmpty(ClientStub::getStoredToken('test_client'));

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
     * @group abstractClients
     * @return ClientStub
     */
    public function testLogin(){
        $Stub = new ClientStub();
        $this->assertEquals(TRUE,$Stub->login());
        $this->assertEquals(array('token'=>'1234'),$Stub->getToken());
        return $Stub;
    }

    /**
     * @depends testLogin
     * @param ClientStub $Stub
     * @covers ::refreshToken
     * @group abstractClients
     * @return ClientStub
     */
    public function testRefreshToken($Stub){
        $this->assertEquals(TRUE,$Stub->refreshToken());
        $this->assertEquals(array('token'=>'5678'),$Stub->getToken());
        $Stub->setToken(static::$token);
        $this->assertEquals(TRUE,$Stub->refreshToken());
        $this->assertEquals(array('token'=>'5678'),$Stub->getToken());
        return $Stub;
    }

    /**
     * @depends testRefreshToken
     * @param ClientStub $Stub
     * @covers ::logout
     * @group abstractClients
     * @return ClientStub
     */
    public function testLogout($Stub){
        $this->assertEquals(TRUE,$Stub->logout());
        $this->assertEmpty($Stub->getToken());
        $Stub->setToken(static::$token);
        $Stub->logout();
        $this->assertEquals(TRUE,$Stub->logout());
        $this->assertEmpty($Stub->getToken());
    }
}
