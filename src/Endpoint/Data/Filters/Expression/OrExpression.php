<?php

namespace Sugarcrm\REST\Endpoint\Data\Filters\Expression;


class OrExpression extends AbstractExpression
{
    const OPERATOR = '$or';

    public function compile()
    {
        return array(
            self::OPERATOR => parent::compile()
        );
    }
}