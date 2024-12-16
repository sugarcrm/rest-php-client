<?php

/**
 * ©[2024] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace Sugarcrm\REST\Endpoint;

use MRussell\REST\Endpoint\Data\EndpointData;
use Sugarcrm\REST\Endpoint\Abstracts\AbstractSmartSugarEndpoint;

/**
 * The Oauth2 Sudo endpoint
 * @package Sugarcrm\REST\Endpoint
 */
class OAuth2Sudo extends AbstractSmartSugarEndpoint
{
    /**
     * @inheritdoc
     */
    protected static $_ENDPOINT_URL = 'oauth2/sudo/$user';

    /**
     * @inheritdoc
     */
    protected static $_DEFAULT_PROPERTIES = [
        self::PROPERTY_HTTP_METHOD => "POST",
        self::PROPERTY_AUTH => true,
        self::PROPERTY_DATA => [
            EndpointData::DATA_PROPERTY_REQUIRED => [
                'client_id' => 'string',
                'platform' => 'string',
            ],
            EndpointData::DATA_PROPERTY_DEFAULTS => [
                'client_id' => 'sugar',
                'platform' => 'base',
            ],
        ],
    ];
}
