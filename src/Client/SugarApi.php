<?php

/**
 * ©[2024] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace Sugarcrm\REST\Client;

use Sugarcrm\REST\Endpoint\Ping;
use Sugarcrm\REST\Endpoint\Module;
use Sugarcrm\REST\Endpoint\ModuleFilter;
use Sugarcrm\REST\Endpoint\Search;
use Sugarcrm\REST\Endpoint\Metadata;
use Sugarcrm\REST\Endpoint\Me;
use Sugarcrm\REST\Endpoint\Enum;
use Sugarcrm\REST\Endpoint\Bulk;
use Sugarcrm\REST\Endpoint\OAuth2Token;
use Sugarcrm\REST\Endpoint\OAuth2Refresh;
use Sugarcrm\REST\Endpoint\OAuth2Logout;
use Sugarcrm\REST\Endpoint\OAuth2Sudo;
use Sugarcrm\REST\Endpoint\Note;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Request;
use MRussell\REST\Client\AbstractClient;
use Sugarcrm\REST\Auth\SugarOAuthController;
use Sugarcrm\REST\Endpoint\Provider\SugarEndpointProvider;

/**
 * The default Sugar 7 REST v10 API implementation
 * @package Sugarcrm\REST\Client\Abstracts\AbstractClient
 * @method Ping ping()
 * @method Module module(string $module = '', string $record_id = '')
 * @method ModuleFilter list(string $module = '')
 * @method Search search()
 * @method Metadata metadata(string $module = '')
 * @method Me me()
 * @method Enum enum(string $module = '', string $field = '')
 * @method Bulk bulk()
 * @method OAuth2Token oauth2Token() - Use login()
 * @method OAuth2Refresh oauth2Refresh() - Use refresh()
 * @method OAuth2Logout oauth2Logout() - Use logout()
 * @method OAuth2Sudo oauth2Sudo() - Use sudo()
 * @method Note Note() -
 */
class SugarApi extends AbstractClient implements PlatformAwareInterface
{
    use PlatformAwareTrait {
        setPlatform as private setRawPlatform;
    }

    public const PLATFORM_BASE = 'base';

    public const API_VERSION = "11";

    public const API_URL = '/rest/v%s/';

    protected static $_DEFAULT_PLATFORM = self::PLATFORM_BASE;

    /**
     * The API Version to be used.
     * Defaults to 10 (for v10), but can be any number above 10,
     * since customizing API allows for additional versioning to allow for duplicate endpoints
     * @var string
     */
    protected $version = self::API_VERSION;

    /**
     * @var SugarOAuthController
     */
    protected $Auth;

    /**
     * Given a sugarcrm server/instance generate the Rest/v10 API Url
     * @param $instance
     * @param int $version
     */
    public static function configureApiUrl($instance, $version = null): string
    {
        $url = 0;
        $version = ($version === null ? self::API_VERSION : $version);
        $instance = preg_replace('/\/rest\/v.+/', '', $instance);
        $url = rtrim($instance, "/") . sprintf(self::API_URL, $version);
        if (preg_match('/^(http|https):\/\//i', $url) === 0) {
            $url = "http://" . $url;
        }

        return $url;
    }

    public function __construct($server = '', array $credentials = [])
    {
        parent::__construct();
        $this->init();
        if (empty($server) && !empty($this->server)) {
            $server = $this->getServer();
        }

        if (!empty($server)) {
            $this->setServer($server);
        }

        $this->setPlatform(static::$_DEFAULT_PLATFORM);
        if (!empty($credentials)) {
            $this->updateAuthCredentials($credentials);
        }
    }

    /**
     * Setup the default Auth Controller and EndpointProvider
     */
    protected function init(): void
    {
        $self = $this;
        $this->getHandlerStack()->push(Middleware::mapRequest(function (Request $request) use ($self) {
            $request = $request->withHeader('User-Agent', 'SugarCRM-REST-PHP-Client');
            return $request->withHeader('X-Sugar-Platform', $self->getPlatform());
        }), 'sugarPlatformHeader');
        $this->initEndpointProvider();
        $this->initAuthProvider();
    }

    protected function initEndpointProvider(): void
    {
        $this->setEndpointProvider(new SugarEndpointProvider());
    }

    protected function initAuthProvider(): void
    {
        $this->setAuth(new SugarOAuthController());

        $Auth = $this->getAuth();
        $Auth->setActionEndpoint('authenticate', $this->oauth2Token());
        $Auth->setActionEndpoint('refresh', $this->oauth2Refresh());
        $Auth->setActionEndpoint('logout', $this->oauth2Logout());
        $Auth->setActionEndpoint('sudo', $this->oauth2Sudo());
    }

    /**
     * @inheritdoc
     */
    protected function setAPIUrl()
    {
        $this->apiURL = self::configureApiUrl($this->server, $this->version);
        $Auth = $this->getAuth();
        foreach ($Auth->getActions() as $action) {
            $EP = $Auth->getActionEndpoint($action);
            $EP->setBaseUrl($this->apiURL);
        }
    }

    /**
     * @return mixed|SugarApi
     */
    public function setPlatform(string $platform)
    {
        $this->setRawPlatform($platform);
        $this->updateAuthCredentials();
        return $this;
    }

    /**
     * Method to update credentials on Auth controller, with current platform
     * @return void
     */
    protected function updateAuthCredentials(array $creds = [])
    {
        if (!isset($creds[SugarOAuthController::OAUTH_PROP_PLATFORM])) {
            $creds[SugarOAuthController::OAUTH_PROP_PLATFORM] = $this->getPlatform();
        }

        $this->getAuth()->updateCredentials($creds);
    }

    /**
     * Helper Method to Login to Sugar Instance
     * @return bool
     */
    public function login($username = null, $password = null)
    {
        $creds = [];
        if ($username !== null) {
            $creds['username'] = $username;
        }

        if ($password !== null) {
            $creds['password'] = $password;
        }

        $this->updateAuthCredentials($creds);
        return $this->getAuth()->authenticate();
    }

    /**
     * Helper Method to Refresh Authentication Token
     * @return bool
     */
    public function refreshToken()
    {
        $creds = $this->getAuth()->getCredentials();
        if (isset($creds['client_id']) && isset($creds['client_secret'])) {
            return $this->getAuth()->refresh();
        }

        return false;
    }

    /**
     * Helper method to Logout of API
     * @return bool
     * @codeCoverageIgnore
     */
    public function logout()
    {
        return $this->getAuth()->logout();
    }

    /**
     * Helper method to Sudo to new user
     * @param $user string
     * @codeCoverageIgnore
     */
    public function sudo($user): bool
    {
        return $this->getAuth()->sudo($user);
    }

    /**
     * Check if authenticated, and attempt Refresh/Login if not
     * @return bool
     */
    public function isAuthenticated()
    {
        $Auth = $this->getAuth();
        $ret = true;
        if (!$Auth->isAuthenticated() && !$this->refreshToken()) {
            $ret = $this->login();
        }

        return $ret;
    }
}
