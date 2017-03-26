<?php

namespace Sugarcrm\REST\Endpoint\Data\Filters\Operator;


class NotNull extends IsNull
{
    const OPERATOR = '$not_null';

    protected static $_OPERATOR = self::OPERATOR;

}