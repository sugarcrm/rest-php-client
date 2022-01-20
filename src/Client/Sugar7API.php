<?php
/**
 * ©[2019] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace Sugarcrm\REST\Client;

use GuzzleHttp\Middleware;
use MRussell\REST\Client\AbstractClient;
use GuzzleHttp\Psr7\Request;
use Sugarcrm\REST\Helpers\Helper;
use Sugarcrm\REST\Auth\SugarOAuthController;
use Sugarcrm\REST\Endpoint\Provider\SugarEndpointProvider;
use Sugarcrm\REST\Storage\SugarStaticStorage;

/**
 * The default Sugar 7 REST v10 API implementation
 * @package Sugarcrm\REST\Client\Abstracts\AbstractClient
 * @method \Sugarcrm\REST\Endpoint\Ping             ping()
 * @method \Sugarcrm\REST\Endpoint\Module           module(string $module = '',string $record_id = '')
 * @method \Sugarcrm\REST\Endpoint\ModuleFilter     list(string $module = '')
 * @method \Sugarcrm\REST\Endpoint\Search           search()
 * @method \Sugarcrm\REST\Endpoint\Metadata         metadata(string $module = '')
 * @method \Sugarcrm\REST\Endpoint\Me               me()
 * @method \Sugarcrm\REST\Endpoint\Enum             enum(string $module = '',string $field = '')
 * @method \Sugarcrm\REST\Endpoint\Bulk             bulk()
 */
class Sugar7API extends AbstractClient implements PlatformAwareInterface
{
    const PLATFORM_BASE = 'base';
    const API_VERSION = "10";
    const API_URL = '/rest/v%s/';

    protected static $_DEFAULT_PLATFORM = self::PLATFORM_BASE;

    /**
     * The API Version to be used.
     * Defaults to 10 (for v10), but can be any number above 10,
     * since customizing API allows for additional versioning to allow for duplicate endpoints
     * @var string
     */
    protected $version = self::API_VERSION;

    /**
     * @var string
     */
    protected $platform = 'base';

    /**
     * @var SugarOAuthController
     */
    protected $Auth;

    /**
     * Given a sugarcrm server/instance generate the Rest/v10 API Url
     * @param $instance
     * @param int $version
     * @return string
     */
    public static function configureAPIURL($instance, $version = null)
    {
        $url = 0;
        $version = ($version === null ? self::API_VERSION : $version);
        $instance = preg_replace('/\/rest\/v.+/', '', $instance);
        $url = rtrim($instance,"/").sprintf(self::API_URL, $version);
        if (preg_match('/^(http|https):\/\//i', $url) === 0) {
            $url = "http://".$url;
        }
        return $url;
    }

    public function __construct($server = '', array $credentials = array())
    {
        parent::__construct();
        $self = $this;
        $this->getHandlerStack()->push(Middleware::mapRequest(function (Request $request)  use ($self){
            return $request->withHeader('X-Sugar-Platform',$self->getPlatform());
        }),'sugarPlatformHeader');
        $this->init();
        if ($server !== '' || !empty($server)){
            $this->setServer($server);
        }
        if (!empty($credentials)){
            $this->getAuth()->updateCredentials($credentials);
        }
    }

    /**
     * Setup the default Auth Controller and EndpointProvider
     */
    protected function init(): void
    {
        $this->initEndpointProvider();
        $this->initAuthProvider();
    }

    /**
     * @return void
     */
    protected function initEndpointProvider(): void
    {
        $this->setEndpointProvider(new SugarEndpointProvider());
    }

    /**
     * @return void
     */
    protected function initAuthProvider(): void
    {
        $this->setAuth(new SugarOAuthController());

        $Auth = $this->getAuth();
        $Auth->setActionEndpoint('authenticate',$this->EndpointProvider->getEndpoint('oauth2Token')->setHttpClient($this->getHttpClient()));
        $Auth->setActionEndpoint('refresh',$this->EndpointProvider->getEndpoint('oauth2Refresh')->setHttpClient($this->getHttpClient()));
        $Auth->setActionEndpoint('logout',$this->EndpointProvider->getEndpoint('oauth2Logout')->setHttpClient($this->getHttpClient()));
        $Auth->setActionEndpoint('sudo',$this->EndpointProvider->getEndpoint('oauth2Sudo')->setHttpClient($this->getHttpClient()));
        $Auth->setStorageController(new SugarStaticStorage());
    }

    /**
     * @inheritdoc
     */
    protected function setAPIUrl()
    {
        $this->apiURL = self::configureAPIURL($this->server, $this->version);
        $Auth = $this->getAuth();
        foreach($Auth->getActions() as $action){
            $EP = $Auth->getActionEndpoint($action);
            $EP->setBaseUrl($this->apiURL);
        }
    }

    /**
     * Helper Method to Login to Sugar Instance
     * @param null $username
     * @param null $password
     * @return bool
     */
    public function login($username = NULL,$password = NULL){
        $creds = array();
        if ($username !== NULL){
            $creds['username'] = $username;
        }
        if ($password !== NULL){
            $creds['password'] = $password;
        }
        if (!empty($creds)){
            $this->getAuth()->updateCredentials($creds);
        }
        return $this->getAuth()->authenticate();
    }

    /**
     * Helper Method to Refresh Authentication Token
     * @return bool
     */
    public function refreshToken(){
        $creds = $this->getAuth()->getCredentials();
        if (isset($creds['client_id']) &&
            isset($creds['client_secret'])){
            return $this->getAuth()->refresh();
        }
        return FALSE;
    }

    /**
     * Helper method to Logout of API
     * @return bool
     */
    public function logout(){
        return $this->getAuth()->logout();
    }

    /**
     * @param $platform
     * @return $this
     */
    public function setPlatform($platform): SugarOAuthController
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
     * @return string
     */
    public function getPlatform(): string
    {
        return $this->platform;
    }

    /**
     * Helper method to Sudo to new user
     * @param $user string
     * @return bool
     */
    public function sudo($user)
    {
        return $this->getAuth()->sudo($user);
    }

    /**
     * Check if authenticated, and attempt Refresh/Login if not
     * @return bool
     * @codeCoverageIgnore
     */
    public function isAuthenticated()
    {
        $Auth = $this->getAuth();
        if ($Auth){
            if (!$Auth->isAuthenticated()){
                if (!$this->refreshToken()){
                    return $this->login();
                }
            }
        } else {
            return FALSE;
        }
        return TRUE;
    }

}
