<?php
/**
 * ©[2017] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace Sugarcrm\REST\Auth;

use MRussell\REST\Auth\Abstracts\AbstractOAuth2Controller;

class SugarOAuthController extends AbstractOAuth2Controller
{
    protected static $_AUTH_HEADER = 'OAuth-Token';

    protected static $_DEFAULT_GRANT_TYPE = self::OAUTH_RESOURCE_OWNER_GRANT;

    /**
     * @inheritdoc
     */
    protected $credentials = array(
        'username' => '',
        'password' => '',
        'client_id' => 'sugar',
        'client_secret' => '',
        'platform' => 'api'
    );

    /**
     * @inheritdoc
     */
    protected function getAuthHeaderValue()
    {
        return $this->token['access_token'];
    }

    /**
     * @inheritdoc
     * Load Stored Token based on Credentials
     */
    public function setCredentials(array $credentials)
    {
        parent::setCredentials($credentials);
        if (!empty($this->credentials)){
            $token = $this->getStoredToken($this->credentials);
            if ($token !== NULL){
                $token = json_decode($token,TRUE);
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
    public function updateCredentials(array $credentials){
        $current = array_replace($this->getCredentials(),$credentials);
        return $this->setCredentials($current);
    }

    /**
     * @inheritdoc
     * @codeCoverageIgnore
     */
    public function authenticate()
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
    public function logout()
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
    public function refresh()
    {
        $return = parent::refresh();
        if ($return){
            $this->storeToken($this->getCredentials(),$this->getToken());
        }
        return $return;
    }
}