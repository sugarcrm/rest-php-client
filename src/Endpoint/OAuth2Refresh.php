<?php
/**
 * ©[2016] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace Sugarcrm\REST\Endpoint;

use MRussell\Http\Request\Curl;
use Sugarcrm\REST\Auth\SugarOAuthController;
use Sugarcrm\REST\Endpoint\Abstracts\AbstractSmartSugarEndpoint;

/**
 * Class OAuth2Refresh
 * @package Sugarcrm\REST\Endpoint
 */
class OAuth2Refresh extends AbstractSmartSugarEndpoint
{
    /**
     * @inheritdoc
     */
    protected static $_ENDPOINT_URL = 'oauth2/refresh';

    /**
     * @inheritdoc
     */
    protected static $_DEFAULT_PROPERTIES = array(
        'auth' => TRUE,
        'httpMethod' => Curl::HTTP_POST,
        'data' => array(
            'required' => array(
                'grant_type' => 'string',
                'client_id' => 'string',
                'client_secret' => 'string',
                'platform' => 'string',
                'refresh_token' => 'string'
            ),
            'defaults' => array(
                'grant_type' => SugarOAuthController::OAUTH_REFRESH_GRANT,
                'client_id' => 'sugar',
                'client_secret' => '',
                'platform' => 'base',
            )
        )
    );
}