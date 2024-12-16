<?php

/**
 * ©[2024] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace Sugarcrm\REST\Endpoint\Data\Filters;

/**
 * Interface for FilterData which should compile down to the expected Filter Array for Sugar API
 * @package Sugarcrm\REST\Endpoint\Data\Filters
 */
interface FilterInterface
{
    /**
     * Compiles the Filter Object an array to be passed to Sugar Filter API
     */
    public function compile(): array;
}
