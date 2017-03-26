<?php

namespace Sugarcrm\REST\Endpoint\Data\Filters\Operator;


class IsNull extends AbstractOperator
{
    const OPERATOR = '$is_null';

    protected static $_OPERATOR = self::OPERATOR;

    public function setValue($value)
    {
        $this->data = array(static::$_OPERATOR);
    }

}