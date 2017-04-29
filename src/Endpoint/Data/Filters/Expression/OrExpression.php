<?php
/**
 * ©[2016] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

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

    public function endOr(){
        return $this->getParentExpression();
    }
}