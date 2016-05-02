<?php
/**
 * ©[2016] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace SugarAPI\SDK\Client\Abstracts;

use SugarAPI\SDK\Client\Interfaces\ClientInterface;
use SugarAPI\SDK\Exception\EntryPoint\EntryPointException;
use SugarAPI\SDK\Helpers\Helpers;
use SugarAPI\SDK\EntryPoint\POST\OAuth2Logout;
use SugarAPI\SDK\Exception\Authentication\AuthenticationException;

/**
 * The Abstract Client implementation for Sugar
 * @package SugarAPI\SDK\Client\Abstracts\AbstractClient
 * @method EPInterface ping()
 * @method EPInterface getRecord(string $module = '')
 * @method EPInterface getAttachment(string $module = '',string $record_id = '')
 * @method EPInterface getChangeLog(string $module = '',string $record_id = '')
 * @method EPInterface filterRelated(string $module = '')
 * @method EPInterface getRelated(string $module = '',string $record_id = '',string $relationship = '',string $related_id = '')
 * @method EPInterface me()
 * @method EPInterface search()
 * @method EPInterface oauth2Token()
 * @method EPInterface oauth2Refresh()
 * @method EPInterface createRecord()
 * @method EPInterface filterRecords()
 * @method EPInterface attachFile()
 * @method EPInterface oauth2Logout()
 * @method EPInterface createRelated()
 * @method EPInterface linkRecords()
 * @method EPInterface bulk()
 * @method EPInterface updateRecord()
 * @method EPInterface favorite()
 * @method EPInterface deleteRecord()
 * @method EPInterface unfavorite()
 * @method EPInterface deleteFile()
 * @method EPInterface unlinkRecords()
 */
abstract class AbstractClient implements ClientInterface {

    /**
     * Array of Statically Bound Tokens for SDK Clients
     * - Allows for reinstating objects in multiple areas, without needing to Sign-in
     * - Allows for multiple client_id's to be used between SDK Clients
     *
     * @var array = array(
     *      $client_id => $token
     * )
     */
    protected static $_STORED_TOKENS = array();


    /**
     * The configured server domain name/url on the SDK Client
     * @var string
     */
    protected $server = '';

    /**
     * The API Url configured on the SDK Client
     * @var string
     */
    protected $apiURL = '';

    /**
     * The full token object returned by the Login method
     * @var \stdClass
     */
    protected $token;

    /**
     * Array of OAuth Creds to be used by SDK Client
     * @var array
     */
    protected $credentials = array();

    /**
     * Token expiration time
     * @var
     */
    protected $expiration;

    /**
     * The list of registered EntryPoints
     * @var array
     */
    protected $entryPoints = array();

    public function __construct($server = '',array $credentials = array()){
        $server = (empty($server)?$this->server:$server);
        $this->setServer($server);
        $credentials = (empty($credentials)?$this->credentials:$credentials);
        $this->setCredentials($credentials);
        $this->registerSDKEntryPoints();
    }

    /**
     * @inheritdoc
     * @param string $server
     */
    public function setServer($server) {
        $this->server = $server;
        $this->apiURL = Helpers::configureAPIURL($this->server);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getAPIUrl() {
        return $this->apiURL;
    }

    /**
     * @inheritdoc
     * Retrieves stored token based on passed in Credentials
     */
    public function setCredentials(array $credentials){
        $this->credentials = $credentials;
        if (isset($this->credentials['client_id'])) {
            $token = static::getStoredToken($this->credentials['client_id']);
            if (!empty($token)) {
                $this->setToken($token);
            }
        }
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setToken(\stdClass $token){
        $this->token = $token;
        $this->expiration = time()+$token->expires_in;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getToken(){
        return $this->token;
    }

    /**
     * @inheritdoc
     */
    public function getCredentials(){
        return $this->credentials;
    }

    /**
     * @inheritdoc
     */
    public function getServer() {
        return $this->server;
    }

    /**
     * @inheritdoc
     */
    public function authenticated(){
        return time() < $this->expiration;
    }

    /**
     * Register the defined EntryPoints in SDK, located in src/EntryPoint/registry.php file
     * @throws EntryPointException
     */
    protected function registerSDKEntryPoints(){
        $entryPoints = Helpers::getSDKEntryPointRegistry();
        foreach ($entryPoints as $funcName => $className){
            $this->registerEntryPoint($funcName, $className);
        }
    }

    /**
     * @param $funcName
     * @param $className
     * @return self
     * @throws EntryPointException
     */
    public function registerEntryPoint($funcName, $className){
        $implements = class_implements($className);
        if (is_array($implements) && in_array('SugarAPI\SDK\EntryPoint\Interfaces\EPInterface',$implements)){
            $this->entryPoints[$funcName] = $className;
        }else{
            throw new EntryPointException($className,'Class must extend SugarAPI\SDK\EntryPoint\Interfaces\EPInterface');
        }
        return $this;
    }

    /**
     * Generates the EntryPoint objects based on a Method name that was called
     * @param $name
     * @param $params
     * @return mixed
     * @throws EntryPointException
     */
    public function __call($name, $params){
        if (array_key_exists($name, $this->entryPoints)){
            $Class = $this->entryPoints[$name];
            $EntryPoint = new $Class($this->apiURL, $params);

            if ($EntryPoint->authRequired() && $this->authenticated()){
                $EntryPoint->setAuth($this->token->access_token);
            }
            return $EntryPoint;
        }else{
            throw new EntryPointException($name,'Unregistered EntryPoint');
        }
    }

    /**
     * @inheritdoc
     * @throws AuthenticationException - When Login request fails
     */
    public function login() {
        if (!empty($this->credentials)) {
            $response = $this->oauth2Token()->execute($this->credentials)->getResponse();
            if ($response->getStatus() == '200') {
                $this->setToken($response->getBody(FALSE));
                static::storeToken($this->token, $this->credentials['client_id']);
                return TRUE;
            } else {
                $error = $response->getBody();
                throw new AuthenticationException("Login Response [" . $error['error'] . "] " . $error['error_message']);
            }
        }
        return FALSE;
    }

    /**
     * @inheritdoc
     * @throws AuthenticationException - When Refresh Request fails
     */
    public function refreshToken(){
        if (isset($this->credentials['client_id'])&&
            isset($this->credentials['client_secret'])&&
            isset($this->token)) {
            $refreshOptions = array(
                'client_id' => $this->credentials['client_id'],
                'client_secret' => $this->credentials['client_secret'],
                'refresh_token' => $this->token->refresh_token
            );
            $response = $this->oauth2Refresh()->execute($refreshOptions)->getResponse();
            if ($response->getStatus() == '200') {
                $this->setToken($response->getBody(FALSE));
                static::storeToken($this->token, $this->credentials['client_id']);
                return TRUE;
            } else {
                $error = $response->getBody();
                throw new AuthenticationException("Refresh Response [" . $error['error'] . "] " . $error['error_message']);
            }
        }
        return FALSE;
    }

    /**
     * @inheritdoc
     * @throws AuthenticationException - When logout request fails
     */
    public function logout(){
        if ($this->authenticated()){
            $response = $this->oauth2Logout()->execute()->getResponse();
            if ($response->getStatus()=='200'){
                unset($this->token);
                static::removeStoredToken($this->credentials['client_id']);
                return TRUE;
            }else{
                $error = $response->getBody();
                throw new AuthenticationException("Logout Response [".$error['error']."] ".$error['message']);
            }
        }
        return FALSE;
    }

    /**
     * @inheritdoc
     * @param \stdClass $token
     */
    public static function storeToken($token, $client_id) {
        static::$_STORED_TOKENS[$client_id] = $token;
        return TRUE;
    }

    /**
     * @inheritdoc
     */
    public static function getStoredToken($client_id) {
        return (isset(static::$_STORED_TOKENS[$client_id])?static::$_STORED_TOKENS[$client_id]:NULL);
    }

    /**
     * @inheritdoc
     */
    public static function removeStoredToken($client_id) {
        unset(static::$_STORED_TOKENS[$client_id]);
        return TRUE;
    }

}