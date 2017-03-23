<?php

namespace Sugarcrm\REST\Endpoint;

use MRussell\Http\Request\Curl;
use MRussell\REST\Endpoint\JSON\SmartEndpoint;

class OAuth2Logout extends SmartEndpoint
{
    protected static $_ENDPOINT_URL = 'oauth2/logout';

    protected static $_DEFAULT_PROPERTIES = array(
        'auth' => TRUE,
        'httpMethod' => Curl::HTTP_POST,
        'data' => array(
            'required' => array(
            ),
            'defaults' => array(
            )
        )
    );
}