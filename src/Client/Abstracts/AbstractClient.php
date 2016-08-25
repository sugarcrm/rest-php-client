<?php
/**
 * ©[2016] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace SugarAPI\SDK\Client\Abstracts;

use SugarAPI\SDK\Client\Interfaces\ClientInterface;
use SugarAPI\SDK\Exception\Endpoint\EndpointException;

/**
 * A Generic Abstract Client
 * @package SugarAPI\SDK\Client\Abstracts\AbstractClient
 */
abstract class AbstractClient implements ClientInterface
{
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
     * @var mixed
     */
    protected $token;

    /**
     * Array of OAuth Creds to be used by SDK Client
     * @var array
     */
    protected $credentials = array();

    /**
     * The list of registered Endpoints
     * @var array
     */
    protected $entryPoints = array();

    /**
     * @inheritdoc
     * @param string $server
     */
    public function setServer($server)
    {
        $this->server = $server;
        $this->setAPIUrl();
        return $this;
    }

    /**
     * Configure the APIUrl Based on the current Server Property
     */
    protected function setAPIUrl()
    {
        $this->apiURL = $this->server;
    }

    /**
     * @inheritdoc
     */
    public function getAPIUrl()
    {
        return $this->apiURL;
    }

    /**
     * @inheritdoc
     */
    public function setCredentials(array $credentials)
    {
        $this->credentials = $credentials;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setToken($token)
    {
        $this->token = $token;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @inheritdoc
     */
    public function getCredentials()
    {
        return $this->credentials;
    }

    /**
     * @inheritdoc
     */
    public function getServer()
    {
        return $this->server;
    }

    /**
     * @inheritdoc
     */
    public function authenticated()
    {
        return !empty($this->token);
    }

    /**
     * @param $funcName
     * @param $className
     * @return self
     * @throws EndpointException
     */
    public function registerEndpoint($funcName, $className)
    {
        $implements = class_implements($className);
        if (is_array($implements) && in_array('SugarAPI\SDK\Endpoint\Interfaces\EPInterface', $implements)) {
            $this->entryPoints[$funcName] = $className;
        } else {
            throw new EndpointException($className, 'Class must extend SugarAPI\SDK\Endpoint\Interfaces\EPInterface');
        }
        return $this;
    }

    /**
     * Generates the Endpoint objects based on a Method name that was called
     * @param $name
     * @param $params
     * @return mixed
     * @throws EndpointException
     */
    public function __call($name, $params)
    {
        if (array_key_exists($name, $this->entryPoints)) {
            $Class = $this->entryPoints[$name];
            if (empty($params)) {
                $Endpoint = new $Class($this->apiURL);
            } else {
                $Endpoint = new $Class($this->apiURL, $params);
            }
            return $Endpoint;
        } else {
            throw new EndpointException($name, 'Unregistered Endpoint');
        }
    }

    /**
     * @inheritdoc
     */
    public function logout()
    {
        $this->token = null;
        return true;
    }

    /**
     * @inheritdoc
     * @param mixed $token
     * @param string $identifier
     */
    public static function storeToken($token, $identifier)
    {
        static::$_STORED_TOKENS[$identifier] = $token;
        return true;
    }

    /**
     * @inheritdoc
     */
    public static function getStoredToken($identifier)
    {
        return (isset(static::$_STORED_TOKENS[$identifier])?static::$_STORED_TOKENS[$identifier]:null);
    }

    /**
     * @inheritdoc
     */
    public static function removeStoredToken($identifier)
    {
        unset(static::$_STORED_TOKENS[$identifier]);
        return true;
    }
}
