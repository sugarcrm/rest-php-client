<?php
/**
 * ©[2016] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace Sugarcrm\REST\Endpoint;


interface SugarEndpointInterface
{
    /**
     * Public method to generate a Compiled Request Object based on current Endpoint State
     * - Useful for troubleshooting
     * - Useful for BULK Api Endpoint
     * @return \MRussell\Http\Request\RequestInterface
     */
    public function compileRequest();
}