<?php
/**
 * ©[2019] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace Sugarcrm\REST\Endpoint\Abstracts;

use MRussell\REST\Endpoint\SmartEndpoint;
use Sugarcrm\REST\Endpoint\SugarEndpointInterface;

/**
 * Provide a smarter interface for Endpoints, to better manage passed in data
 * @package Sugarcrm\REST\Endpoint\Abstracts
 */
class AbstractSmartSugarEndpoint extends SmartEndpoint implements SugarEndpointInterface
{
    /**
     * @inheritdoc
     * @codeCoverageIgnore
     */
    public function compileRequest()
    {
        return $this->buildRequest();
    }
}