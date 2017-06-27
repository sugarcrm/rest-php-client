<?php
/**
 * ©[2017] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace Sugarcrm\REST\Endpoint;

use MRussell\Http\Request\Curl;
use Sugarcrm\REST\Endpoint\Abstracts\AbstractSmartSugarEndpoint;

/**
 * Class OAuth2Logout
 * @package Sugarcrm\REST\Endpoint
 */
class OAuth2Logout extends AbstractSmartSugarEndpoint
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