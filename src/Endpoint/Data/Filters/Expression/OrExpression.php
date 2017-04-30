<?php
/**
 * ©[2016] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace Sugarcrm\REST\Endpoint\Data\Filters\Expression;

/**
 * Class OrExpression
 * @package Sugarcrm\REST\Endpoint\Data\Filters\Expression
 */
class OrExpression extends AbstractExpression
{
    const OPERATOR = '$or';

    /**
     * @inheritdoc
     */
    public function compile()
    {
        return array(
            self::OPERATOR => parent::compile()
        );
    }

    /**
     * Human Friendly Expression End, allow you to traverse back up the Filter expression
     * @return AbstractExpression
     * @codeCoverageIgnore
     */
    public function endOr(){
        return $this->getParentExpression();
    }
}