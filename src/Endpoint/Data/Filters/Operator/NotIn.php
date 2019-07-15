<?php
/**
 * ©[2019] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace Sugarcrm\REST\Endpoint\Data\Filters\Operator;

/**
 * Class NotIn
 * @package Sugarcrm\REST\Endpoint\Data\Filters\Operator
 */
class NotIn extends AbstractOperator
{
    const OPERATOR = '$not_in';

    protected static $_OPERATOR = self::OPERATOR;

}