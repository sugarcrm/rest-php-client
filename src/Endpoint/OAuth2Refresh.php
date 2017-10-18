<?php
/**
 * ©[2017] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace Sugarcrm\REST\Endpoint;

use MRussell\Http\Request\Curl;
use MRussell\REST\Endpoint\Data\EndpointData;
use Sugarcrm\REST\Auth\SugarOAuthController;

/**
 * The OAuth2 Refresh Token Endpoint
 * @package Sugarcrm\REST\Endpoint
 */
class OAuth2Refresh extends OAuth2Token
{
    /**
     * @inheritdoc
     */
    protected static $_DEFAULT_PROPERTIES = array(
        self::PROPERTY_AUTH => TRUE,
        self::PROPERTY_HTTP_METHOD => Curl::HTTP_POST,
        self::PROPERTY_DATA => array(
            EndpointData::DATA_PROPERTY_REQUIRED => array(
                'grant_type' => 'string',
                'client_id' => 'string',
                'client_secret' => 'string',
                'platform' => 'string',
                'refresh_token' => 'string'
            ),
            EndpointData::DATA_PROPERTY_DEFAULTS => array(
                'grant_type' => SugarOAuthController::OAUTH_REFRESH_GRANT,
                'client_id' => 'sugar',
                'client_secret' => '',
                'platform' => 'base',
            )
        )
    );
}