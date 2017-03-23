<?php

namespace Sugarcrm\REST\Endpoint;

use MRussell\Http\Request\Curl;
use MRussell\REST\Endpoint\JSON\SmartEndpoint;
use Sugarcrm\REST\Auth\SugarOAuthController;

class OAuth2Token extends SmartEndpoint
{
    protected static $_ENDPOINT_URL = 'oauth2/token';

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