<?php
/**
 * ©[2022] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace Sugarcrm\REST\Endpoint\Data\Filters\Expression;

/**
 * AndExpression provides the basic AND wrapper for filter data
 * @package Sugarcrm\REST\Endpoint\Data\Filters\Expression
 */
class AndExpression extends AbstractExpression
{
    public const OPERATOR = '$and';

    /**
     * @inheritdoc
     */
    public function compile(): array
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
    public function endAnd()
    {
        return $this->getParentExpression();
    }
}
