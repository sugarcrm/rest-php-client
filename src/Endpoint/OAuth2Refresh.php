<?php

namespace Sugarcrm\REST\Endpoint;

use MRussell\Http\Request\Curl;
use MRussell\REST\Endpoint\JSON\SmartEndpoint;
use Sugarcrm\REST\Auth\SugarOAuthController;

class OAuth2Refresh extends SmartEndpoint
{
    protected static $_ENDPOINT_URL = 'oauth2/refresh';

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