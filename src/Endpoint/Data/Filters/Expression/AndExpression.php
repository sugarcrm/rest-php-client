<?php
/**
 * ©[2017] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace Sugarcrm\REST\Endpoint\Data\Filters\Expression;

/**
 * Class AndExpression
 * @package Sugarcrm\REST\Endpoint\Data\Filters\Expression
 */
class AndExpression extends AbstractExpression
{
    const OPERATOR = '$and';

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
    public function endAnd(){
        return $this->getParentExpression();
    }
}