<?php

namespace Sugarcrm\REST\Endpoint\Data\Filters\Operator;


class GreaterThanOrEqual extends AbstractOperator
{
    const OPERATOR = '$gte';

    protected static $_OPERATOR = self::OPERATOR;

}