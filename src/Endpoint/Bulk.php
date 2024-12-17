<?php

/**
 * ©[2024] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace Sugarcrm\REST\Endpoint;

use Sugarcrm\REST\Endpoint\Data\BulkRequest;
use MRussell\REST\Endpoint\Data\EndpointData;
use Sugarcrm\REST\Endpoint\Abstracts\AbstractSmartSugarEndpoint;

/**
 * Bulk Endpoint allows for submitting multiple REST Requests in a single request
 * - Consumes other Endpoint Objects for ease of use
 * @package Sugarcrm\REST\Endpoint
 */
class Bulk extends AbstractSmartSugarEndpoint
{
    /**
     * @inheritdoc
     */
    protected static $_ENDPOINT_URL = 'bulk';

    /**
     * @inheritdoc
     */
    protected static $_DATA_CLASS = BulkRequest::class;

    /**
     * @var array
     */
    protected static $_DEFAULT_PROPERTIES = [
        self::PROPERTY_AUTH => true,
        self::PROPERTY_HTTP_METHOD => "POST",
        self::PROPERTY_DATA => [
            EndpointData::DATA_PROPERTY_REQUIRED => [
                'requests' => 'array',
            ],
            EndpointData::DATA_PROPERTY_DEFAULTS => [],
        ],
    ];
}
