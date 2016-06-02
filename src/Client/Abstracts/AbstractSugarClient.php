<?php
/**
 * ©[2016] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace SugarAPI\SDK\Client\Abstracts;

use SugarAPI\SDK\Exception\Authentication\AuthenticationException;
use SugarAPI\SDK\Exception\SDKException;
use SugarAPI\SDK\Helpers\Helpers;


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
abstract class AbstractSugarClient extends AbstractClient {

    /**
     * @inheritdoc
     * @var array
     */
    protected $credentials = array(
        'username' => '',
        'password' => '',
        'client_id' => '',
        'client_secret' => '',
        'platform' => ''
    );

    /**
     * Token expiration time
     * @var
     */
    protected $expiration;

    /**
     * The API Version to be used.
     * Defaults to 10 (for v10), but can be any number above 10, since customizing API allows for additional versioning to allow for duplicate entrypoints
     * @var
     */
    protected $apiVersion = 10;

    public function __construct($server = '',array $credentials = array()){
        $server = (empty($server)?$this->server:$server);
        $this->setServer($server);
        $credentials = (empty($credentials)?$this->credentials:$credentials);
        $this->setCredentials($credentials);
        $this->registerSDKEndpoints();
    }

    /**
     * @inheritdoc
     */
    protected function setAPIUrl() {
        $this->apiURL = Helpers::configureAPIURL($this->server,$this->apiVersion);
    }

    /**
     * @param $version
     * @return $this
     */
    public function setVersion($version){
        $this->apiVersion = intval($version);
        $this->setAPIUrl();
        return $this;
    }

    public function getVersion(){
        return $this->apiVersion;
    }

    /**
     * @inheritdoc
     * Overrides only the credentials properties passed in, instead of entire credentials array
     * Retrieves stored token based on passed in Credentials
     */
    public function setCredentials(array $credentials){
        foreach ($this->credentials as $key => $value){
            if (isset($credentials[$key])){
                $this->credentials[$key] = $credentials[$key];
            }
        }
        parent::setCredentials($this->credentials);
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
    public function setToken($token){
        if ($token instanceof \stdClass) {
            parent::setToken($token);
            $this->expiration = time() + $token->expires_in;
            return $this;
        }else{
            throw new SDKException('Sugar API Client requires Token to be of type \stdClass');
        }
    }

    /**
     * @inheritdoc
     */
    public function authenticated(){
        return time() < $this->expiration;
    }

    /**
     * Register the defined Endpoints in SDK, located in src/Endpoint/registry.php file
     * @throws EndpointException
     */
    protected function registerSDKEndpoints(){
        $entryPoints = Helpers::getSDKEndpointRegistry();
        foreach ($entryPoints as $funcName => $className){
            $this->registerEndpoint($funcName, $className);
        }
    }

    /**
     * @inheritdoc
     * Adds Auth token to EntryPoint if Auth is required
     */
    public function __call($name, $params){
        $Endpoint = parent::__call($name,$params);

        if ($Endpoint->authRequired() && $this->authenticated()){
            $Endpoint->setAuth($this->token->access_token);
        }
        return $Endpoint;
    }

    /**
     * @inheritdoc
     * @throws AuthenticationException - When Login request fails
     */
    public function login() {
        if (!(empty($this->credentials['username'])||
            empty($this->credentials['password'])||
            empty($this->credentials['client_id'])||
            !isset($this->credentials['client_secret']))) {
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
                if (isset($this->credentials['client_id'])) {
                    static::removeStoredToken($this->credentials['client_id']);
                }
                return parent::logout();
            }else{
                $error = $response->getBody();
                throw new AuthenticationException("Logout Response [".$error['error']."] ".$error['message']);
            }
        }
        return FALSE;
    }

}