<?php
/**
 * ©[2017] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace Sugarcrm\REST\Endpoint;


use MRussell\Http\Request\JSON;
use MRussell\REST\Endpoint\Data\EndpointData;
use Sugarcrm\REST\Endpoint\Abstracts\AbstractSmartSugarEndpoint;

class OAuth2Sudo extends AbstractSmartSugarEndpoint
{
    /**
     * @inheritdoc
     */
    protected static $_ENDPOINT_URL = 'oauth2/sudo/$user';

    /**
     * @inheritdoc
     */
    protected static $_DEFAULT_PROPERTIES = array(
        self::PROPERTY_HTTP_METHOD => JSON::HTTP_POST,
        self::PROPERTY_AUTH => true,
        self::PROPERTY_DATA => array(
            EndpointData::DATA_PROPERTY_REQUIRED => array(
                'client_id' => 'string',
                'platform' => 'string'
            ),
            EndpointData::DATA_PROPERTY_DEFAULTS => array(
                'client_id' => 'sugar',
                'platform' => 'base'
            )
        )
    );
}