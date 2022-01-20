<?php
/**
 * ©[2019] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace Sugarcrm\REST\Endpoint;

/**
 * The base interface for Sugar Endpoints, which adds in functionality for use with Bulk API Endpoint
 * @package Sugarcrm\REST\Endpoint
 */
interface SugarEndpointInterface
{
    /**
     * Public method to generate a Compiled Request Object based on current Endpoint State
     * - Useful for troubleshooting
     * - Useful for BULK Api Endpoint
     * @return \GuzzleHttp\Psr7\Request
     */
    public function compileRequest();
}