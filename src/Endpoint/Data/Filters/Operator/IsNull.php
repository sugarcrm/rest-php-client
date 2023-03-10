<?php
/**
 * ©[2022] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace Sugarcrm\REST\Endpoint\Data\Filters\Operator;

/**
 * @package Sugarcrm\REST\Endpoint\Data\Filters\Operator
 */
class IsNull extends AbstractOperator
{
    public const OPERATOR = '$is_null';

    protected static $_OPERATOR = self::OPERATOR;

    public function setValue($value)
    {
        $this->value = null;
        return $this;
    }

    public function compile(): array
    {
        return array(
            $this->getField() => array(static::$_OPERATOR)
        );
    }
}
