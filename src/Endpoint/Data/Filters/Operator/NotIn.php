<?php

namespace Sugarcrm\REST\Endpoint\Data\Filters\Operator;


class NotIn extends AbstractOperator
{
    const OPERATOR = '$not_in';

    protected static $_OPERATOR = self::OPERATOR;

}