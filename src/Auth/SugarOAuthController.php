<?php

namespace Sugarcrm\REST\Auth;

use MRussell\REST\Auth\Abstracts\AbstractOAuth2Controller;

class SugarOAuthController extends AbstractOAuth2Controller
{
    protected static $_OAUTH_HEADER = 'OAuth-Token';

    protected static $_TOKEN_VALUE = '%s';

    protected static $_DEFAULT_GRANT_TYPE = self::OAUTH_RESOURCE_OWNER_GRANT;

    /**
     * @inheritdoc
     * @var array
     */
    protected $credentials = array(
        'username' => '',
        'password' => '',
        'client_id' => 'sugar',
        'client_secret' => '',
        'platform' => 'api'
    );

    public function updateCredentials(array $credentials){
        $current = $this->getCredentials();
        foreach($current as $key => $value){
            if (isset($credentials[$key])){
                $current[$key] = $credentials[$key];
            }
        }
        return $this->setCredentials($current);
    }


}