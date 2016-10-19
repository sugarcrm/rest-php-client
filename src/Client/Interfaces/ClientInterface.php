<?php
/**
 * ©[2016] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace SugarAPI\SDK\Client\Interfaces;

use SugarAPI\SDK\Exception\Authentication\AuthenticationException;

interface ClientInterface
{
    /**
     * Set the server on the Client, and configure the API Url if necessary
     * @param $server
     * @return $this
     */
    public function setServer($server);

    /**
     * Get the server configured on SDK Client
     * @return mixed
     */
    public function getServer();

    /**
     * Get the configured API Url on the SDK Client
     * @return string
     */
    public function getAPIUrl();

    /**
     * Register Methods to Endpoint objects on the SDK Client
     * @param $function - method/function name to be registered on the SDK Client
     * @param $Endpoint - EPInterface Class Name
     * @return $this
     */
    public function registerEndpoint($function, $Endpoint);

    /**
     * Login to the configured SugarCRM server
     * @throws AuthenticationException
     * @return boolean
     */
    public function login();

    /**
     * Logout of the configured SugarCRM server
     * @throws AuthenticationException
     * @return boolean
     */
    public function logout();

    /**
     * Refresh the authentication token on the configured SugarCRM server
     * @throws AuthenticationException
     * @return boolean
     */
    public function refreshToken();

    /**
     * Store the SDK Clients authentication token
     * @param mixed $token
     * @param mixed $identifier
     * @return boolean
     */
    public static function storeToken($token, $identifier);

    /**
     * Get an SDK Clients authentication Token from Storage
     * @param mixed $identifier
     * @return mixed
     */
    public static function getStoredToken($identifier);

    /**
     * Remove the stored SDK Clients authentication token
     * @param mixed $identifier
     * @return boolean
     */
    public static function removeStoredToken($identifier);

    /**
     * Get the Token on the SDK Client
     * @return \stdClass
     */
    public function getToken();

    /**
     * Get the credentials array configured on the SDK Client
     * @return array
     */
    public function getCredentials();

    /**
     * Set the credentials for the SDK Client
     * @param array $credentials
     * @return $this
     */
    public function setCredentials(array $credentials);

    /**
     * Set the current AuthToken and Auth Expiration properties
     * @param mixed $token
     */
    public function setToken($token);

    /**
     * Check if SDK Client is authentication
     * @return boolean
     */
    public function authenticated();
}
