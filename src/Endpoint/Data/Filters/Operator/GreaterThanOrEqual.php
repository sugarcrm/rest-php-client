<?php

/**
 * ©[2024] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace Sugarcrm\REST\Endpoint\Data\Filters\Operator;

/**
 * @package Sugarcrm\REST\Endpoint\Data\Filters\Operator
 */
class GreaterThanOrEqual extends AbstractOperator
{
    public const OPERATOR = '$gte';

    protected static $_OPERATOR = self::OPERATOR;
}
