<?php
/**
 * ©[2019] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace Sugarcrm\REST\Endpoint\Abstracts;

use MRussell\REST\Endpoint\SmartEndpoint;
use Sugarcrm\REST\Endpoint\SugarEndpointInterface;
use Sugarcrm\REST\Endpoint\Traits\CompileRequestTrait;

/**
 * Provide a smarter interface for Endpoints, to better manage passed in data
 * @package Sugarcrm\REST\Endpoint\Abstracts
 */
class AbstractSmartSugarEndpoint extends SmartEndpoint implements SugarEndpointInterface
{
    use CompileRequestTrait;
}