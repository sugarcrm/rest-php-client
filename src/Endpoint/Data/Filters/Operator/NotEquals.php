<?php

namespace Sugarcrm\REST\Endpoint\Data\Filters\Operator;


class NotEquals extends AbstractOperator
{
    const OPERATOR = '$not_equals';

    protected static $_OPERATOR = self::OPERATOR;

}