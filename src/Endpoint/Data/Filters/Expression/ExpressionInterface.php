<?php
/**
 * User: mrussell
 * Date: 4/30/17
 * Time: 3:14 PM
 */

namespace Sugarcrm\REST\Endpoint\Data\Filters\Expression;


interface ExpressionInterface
{
    /**
     * Clear out Filters included in Expression
     * @return mixed
     */
    public function clear();
}