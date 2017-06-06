<?php
/**
 * ©[2016] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace Sugarcrm\REST\Endpoint;

use MRussell\Http\Request\Curl;
use Sugarcrm\REST\Auth\SugarOAuthController;
use Sugarcrm\REST\Endpoint\Abstracts\AbstractSmartSugarEndpoint;

/**
 * Class OAuth2Token
 * @package Sugarcrm\REST\Endpoint
 */
class OAuth2Token extends AbstractSmartSugarEndpoint
{
    /**
     * @inheritdoc
     */
    protected static $_ENDPOINT_URL = 'oauth2/token';

    /**
     * @inheritdoc
     */
    protected static $_DEFAULT_PROPERTIES = array(
        'auth' => FALSE,
        'httpMethod' => Curl::HTTP_POST,
        'data' => array(
            'required' => array(
                'grant_type' => 'string',
                'client_id' => 'string',
                'client_secret' => 'string',
                'platform' => 'string',
                'username' => 'string',
                'password' => 'string'
            ),
            'defaults' => array(
                'grant_type' => SugarOAuthController::OAUTH_RESOURCE_OWNER_GRANT,
                'client_id' => 'sugar',
                'client_secret' => '',
                'platform' => 'base'
            )
        )
    );
}