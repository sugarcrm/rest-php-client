<?php
/**
 * ©[2016] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace Sugarcrm\REST\Auth;

use MRussell\REST\Auth\Abstracts\AbstractOAuth2Controller;

class SugarOAuthController extends AbstractOAuth2Controller
{
    protected static $_OAUTH_HEADER = 'OAuth-Token';

    protected static $_TOKEN_VALUE = '%s';

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

    public function updateCredentials(array $credentials){
        $current = array_replace($this->getCredentials(),$credentials);
        return $this->setCredentials($current);
    }


}