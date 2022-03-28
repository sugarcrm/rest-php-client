<?php
/**
 * ©[2022] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace Sugarcrm\REST\Endpoint\Data\Filters;

interface FilterInterface
{
    /**
     * Compiles the Filter Object an array to be passed to Sugar Filter API
     * @return array
     */
    public function compile();
}