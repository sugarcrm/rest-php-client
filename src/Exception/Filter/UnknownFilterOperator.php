<?php
/**
 * ©[2022] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace Sugarcrm\REST\Exception\Filter;

use MRussell\REST\Exception\Endpoint\EndpointException;

class UnknownFilterOperator extends EndpointException
{
    protected $message = 'Unknown Filter Operator: %s';
}