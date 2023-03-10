<?php
/**
 * ©[2022] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace Sugarcrm\REST\Endpoint\Data\Filters\Operator;

/**
 * @package Sugarcrm\REST\Endpoint\Data\Filters\Operator
 */
class NotEquals extends AbstractOperator
{
    public const OPERATOR = '$not_equals';

    protected static $_OPERATOR = self::OPERATOR;
}
