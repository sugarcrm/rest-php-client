<?php
/**
 * ©[2022] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace Sugarcrm\REST\Endpoint\Data\Filters\Operator;


class IsNull extends AbstractOperator
{
    const OPERATOR = '$is_null';

    protected static $_OPERATOR = self::OPERATOR;

    public function setValue($value)
    {
        $this->value = NULL;
        return $this;
    }

    public function compile()
    {
        return array(
            $this->getField() => array(static::$_OPERATOR)
        );
    }

}