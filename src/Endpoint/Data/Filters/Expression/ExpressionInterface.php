<?php

/**
 * ©[2024] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace Sugarcrm\REST\Endpoint\Data\Filters\Expression;

/**
 * The Expression Interface defines the basic API needed for an Expression object used in the Filter API Data Layer
 * @package Sugarcrm\REST\Endpoint\Data\Filters\Expression
 **/
interface ExpressionInterface
{
    /**
     * Compiles the Filter Expression into an array to be passed to Sugar Filter API
     */
    public function compile(): array;

    /**
     * Clear out Filters included in Expression
     * @return $this
     */
    public function clear();
}
