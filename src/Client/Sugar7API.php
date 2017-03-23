<?php
/**
 * ©[2016] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace Sugarcrm\REST\Client;

use MRussell\REST\Client\AbstractClient;
use MRussell\REST\Endpoint\Interfaces\EndpointInterface;
use Sugarcrm\REST\Helpers\Helpers;
use Sugarcrm\REST\Auth\SugarOAuthController;
use Sugarcrm\REST\Endpoint\Provider\SugarEndpointProvider;
use Sugarcrm\REST\Endpoint\Module;
use Sugarcrm\REST\Endpoint\ModuleFilter;
use Sugarcrm\REST\Endpoint\Search;
use Sugarcrm\REST\Endpoint\Metadata;
use Sugarcrm\REST\Endpoint\User;

/**
 * The Abstract Client implementation for Sugar
 * @package Sugarcrm\REST\Client\Abstracts\AbstractClient
 * @method EndpointInterface ping()
 * @method Module       module(string $module = '',string $record_id = '')
 * @method ModuleFilter filter(string $module = '')
 * @method Search       search()
 * @method Metadata     metadata(string $module = '')
 * @method User         user(string $user_id)
*/
class Sugar7API extends AbstractClient
{
    /**
     * The API Version to be used.
     * Defaults to 10 (for v10), but can be any number above 10,
     * since customizing API allows for additional versioning to allow for duplicate endpoints
     * @var int
     */
    protected $version = 10;

    /**
     * @var SugarOAuthController
     */
    protected $Auth;

    public function __construct($server = '', array $credentials = array())
    {
        $this->init();
        if ($server !== '' || !empty($server)){
            $this->setServer($server);
        }
        if (!empty($credentials)){
            $this->Auth->updateCredentials($credentials);
        }
    }

    /**
     * Setup the default Auth Controller and EndpointProvider
     */
    protected function init(){
        $this->setAuth(new SugarOAuthController());
        $this->setEndpointProvider(new SugarEndpointProvider());
        $this->Auth->setActionEndpoint('authenticate',$this->EndpointProvider->getEndpoint('oauth2Token'));
        $this->Auth->setActionEndpoint('refresh',$this->EndpointProvider->getEndpoint('oauth2Refresh'));
        $this->Auth->setActionEndpoint('logout',$this->EndpointProvider->getEndpoint('oauth2Logout'));
    }

    /**
     * @inheritdoc
     */
    protected function setAPIUrl()
    {
        $this->apiURL = Helpers::configureAPIURL($this->server, $this->version);
        foreach($this->Auth->getActions() as $action){
            $EP = $this->Auth->getActionEndpoint($action);
            $EP->setBaseUrl($this->apiURL);
        }
    }

    public function login($username = NULL,$password = NULL){
        $creds = array();
        if ($username !== NULL){
            $creds['username'] = $username;
        }
        if ($password !== NULL){
            $creds['password'] = $password;
        }
        if (!empty($creds)){
            $this->Auth->updateCredentials($creds);
        }
        $this->Auth->authenticate();
    }

}
