<?php
/**
 * ©[2022] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace Sugarcrm\REST\Endpoint;


use MRussell\REST\Endpoint\Data\EndpointData;
use Sugarcrm\REST\Auth\SugarOAuthController;
use Sugarcrm\REST\Endpoint\Abstracts\AbstractSmartSugarEndpoint;

/**
 * The OAuth2 Token REST Endpoint
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
        self::PROPERTY_AUTH => true,
        self::PROPERTY_HTTP_METHOD => "POST",
        self::PROPERTY_DATA => array(
            EndpointData::DATA_PROPERTY_REQUIRED => array(
                'grant_type' => 'string',
                'client_id' => 'string',
                'client_secret' => 'string',
                'platform' => 'string',
                'username' => 'string',
                'password' => 'string'
            ),
            EndpointData::DATA_PROPERTY_DEFAULTS => array(
                'grant_type' => SugarOAuthController::OAUTH_RESOURCE_OWNER_GRANT,
                'client_id' => 'sugar',
                'client_secret' => '',
                'platform' => 'base'
            )
        )
    );
}