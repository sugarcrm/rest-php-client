<?php

namespace Sugarcrm\REST\Endpoint\Data\Filters\Expression;

class AndExpression extends AbstractExpression
{
    const OPERATOR = '$and';

    public function compile()
    {
        return array(
            self::OPERATOR => parent::compile()
        );
    }

    public function endAnd(){
        return $this->getParentExpression();
    }
}