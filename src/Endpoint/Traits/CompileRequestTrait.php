<?php

/**
 * ©[2024] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace Sugarcrm\REST\Endpoint\Traits;

/**
 * @package Sugarcrm\REST\Endpoint\Traits
 */
trait CompileRequestTrait
{
    /**
     * Public interface for building the Guzzle Request object for an Endpoint
     * @implements SugarEndpointInterface
     */
    public function compileRequest()
    {
        return $this->buildRequest();
    }
}
