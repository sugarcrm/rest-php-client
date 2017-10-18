<?php
/**
 * ©[2017] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace Sugarcrm\REST\Endpoint;

use MRussell\Http\Request\JSON;
use Sugarcrm\REST\Endpoint\Abstracts\AbstractSugarEndpoint;

/**
 * Enum Endpoint provides access to the defined Enum values for a given module field
 * @package Sugarcrm\REST\Endpoint
 */
class Enum extends AbstractSugarEndpoint
{
    protected static $_ENDPOINT_URL = '$module/enum/$field';

    protected static $_DEFAULT_PROPERTIES = array(
        self::PROPERTY_AUTH => true,
        self::PROPERTY_HTTP_METHOD => JSON::HTTP_GET
    );
}