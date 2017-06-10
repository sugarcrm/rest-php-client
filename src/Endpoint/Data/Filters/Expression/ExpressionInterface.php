<?php
/**
 * ©[2017] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace Sugarcrm\REST\Endpoint\Data\Filters\Expression;


interface ExpressionInterface
{
    /**
     * Clear out Filters included in Expression
     * @return mixed
     */
    public function clear();
}