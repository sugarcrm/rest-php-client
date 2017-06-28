<?php
/**
 * ©[2017] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace Sugarcrm\REST\Endpoint;

use MRussell\Http\Request\JSON;
use Sugarcrm\REST\Endpoint\Abstracts\AbstractSugarEndpoint;

class Enum extends AbstractSugarEndpoint
{
    protected static $_ENDPOINT_URL = '$module/enum/$field';

    protected static $_DEFAULT_PROPERTIES = array(
        'auth' => true,
        'httpMethod' => JSON::HTTP_GET
    );
}