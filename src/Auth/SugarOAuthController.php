<?php
/**
 * ©[2019] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace Sugarcrm\REST\Auth;

use MRussell\REST\Auth\Abstracts\AbstractOAuth2Controller;
use MRussell\REST\Auth\AuthControllerInterface;
use MRussell\REST\Endpoint\Interfaces\EndpointInterface;
use Sugarcrm\REST\Client\PlatformAwareInterface;
use Sugarcrm\REST\Client\PlatformAwareTrait;
use Sugarcrm\REST\Client\SugarApi;

/**
 * The Authentication Controller for the Sugar 7 REST Client
 * - Manages authenticating to API
 * - Manages refreshing API token for continuous access
 * - Manages logout
 * - Configures Endpoints that require auth, so that Requests are properly formatted
 * @package Sugarcrm\REST\Auth
 */
class SugarOAuthController extends AbstractOAuth2Controller implements PlatformAwareInterface
{
    use PlatformAwareTrait;

    const ACTION_SUGAR_SUDO = 'sudo';

    const OAUTH_PROP_PLATFORM = 'platform';

    protected static $_AUTH_HEADER = 'OAuth-Token';

    protected static $_DEFAULT_GRANT_TYPE = self::OAUTH_RESOURCE_OWNER_GRANT;

    protected static $_DEFAULT_SUGAR_AUTH_ACTIONS = array(
        self::ACTION_SUGAR_SUDO
    );

    /**
     * @inheritdoc
     */
    protected $credentials = array(
        'username' => '',
        'password' => '',
        'client_id' => 'sugar',
        'client_secret' => '',
    );

    /**
     * @inheritdoc
     */
    public function __construct()
    {
        parent::__construct();
        $this->setPlatform(SugarApi::PLATFORM_BASE);
        foreach (static::$_DEFAULT_SUGAR_AUTH_ACTIONS as $action) {
            $this->actions[] = $action;
        }
    }

    /**
     * @param $platform
     * @return $this
     */
    public function setPlatform(string $platform)
    {
        $this->platform = $platform;
        if (!isset($this->credentials[self::OAUTH_PROP_PLATFORM]) ||
            $this->credentials[self::OAUTH_PROP_PLATFORM] !== $platform){
            $this->credentials[self::OAUTH_PROP_PLATFORM] = $this->platform;
            $this->setCredentials($this->credentials);
        }

        return $this;
    }

    /**
     * @inheritdoc
     */
    protected function getAuthHeaderValue(): string
    {
        return $this->token['access_token'];
    }

    /**
     * @inheritdoc
     * Load Stored Token based on Credentials
     */
    public function setCredentials(array $credentials): AuthControllerInterface
    {
        if (isset($credentials[self::OAUTH_PROP_PLATFORM]) &&
            $credentials[self::OAUTH_PROP_PLATFORM] !== $this->platform){
            $this->setPlatform($credentials[self::OAUTH_PROP_PLATFORM]);
        }
        parent::setCredentials($credentials);
        if (!empty($this->credentials)){
            $token = $this->getStoredToken($this->credentials);
            if ($token !== NULL){
                if (is_array($token)){
                    $this->setToken($token);
                }
            }
        }
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function updateCredentials(array $credentials): AuthControllerInterface
    {
        $current = array_replace($this->getCredentials(),$credentials);
        return $this->setCredentials($current);
    }

    /**
     * @inheritdoc
     * @codeCoverageIgnore
     */
    public function authenticate(): bool
    {
        $return = parent::authenticate();
        if ($return){
            $this->storeToken($this->getCredentials(),$this->getToken());
        }
        return $return;
    }

    /**
     * @inheritdoc
     * @codeCoverageIgnore
     */
    public function logout(): bool
    {
        $return = parent::logout();
        if ($return){
            $this->removeStoredToken($this->getCredentials());
        }
        return $return;
    }

    /**
     * @inheritdoc
     * @codeCoverageIgnore
     */
    public function refresh(): bool
    {
        $return = parent::refresh();
        if ($return){
            $this->storeToken($this->getCredentials(),$this->getToken());
        }
        return $return;
    }

    /**
     * Refreshes the OAuth 2 Token
     * @param $user string
     * @return bool
     * @throws InvalidToken
     */
    public function sudo($user): bool
    {
        if (isset($this->token['access_token'])) {
            $Endpoint = $this->getActionEndpoint(self::ACTION_SUGAR_SUDO);
            if ($Endpoint !== null) {
                $Endpoint = $this->configureSudoEndpoint($Endpoint, $user);
                $response = $Endpoint->execute()->getResponse();
                if ($response->getStatusCode() == 200) {
                    $this->parseResponseToToken(self::ACTION_SUGAR_SUDO,$response);
                    $creds = $this->getCredentials();
                    $creds['sudo'] = $user;
                    $this->storeToken($creds,$this->getToken());
                    return true;
                }
            }
        }
        return true;
    }

    /**
     * @inheritDoc
     */
    public function reset(): AuthControllerInterface
    {
        $this->setPlatform(SugarApi::PLATFORM_BASE);
        return parent::reset();
    }

    /**
     * Configure the Sudo Endpoint
     * @param EndpointInterface $Endpoint
     * @param $user
     * @return EndpointInterface
     */
    protected function configureSudoEndpoint(EndpointInterface $Endpoint,$user): EndpointInterface
    {
        $Endpoint->setUrlArgs(array($user));
        $data = array();
        $creds = $this->getCredentials();
        $data['platform'] = $this->platform;
        $data['client_id'] = $creds['client_id'];
        $Endpoint->setData($data);
        return $Endpoint;
    }
}