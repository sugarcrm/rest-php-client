<?php

/**
 * ©[2024] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace Sugarcrm\REST\Endpoint\Data\Filters\Expression;

use Sugarcrm\REST\Endpoint\Data\Filters\Operator\Equals;
use Sugarcrm\REST\Endpoint\Data\Filters\Operator\NotEquals;
use Sugarcrm\REST\Endpoint\Data\Filters\Operator\Starts;
use Sugarcrm\REST\Endpoint\Data\Filters\Operator\Ends;
use Sugarcrm\REST\Endpoint\Data\Filters\Operator\Contains;
use Sugarcrm\REST\Endpoint\Data\Filters\Operator\In;
use Sugarcrm\REST\Endpoint\Data\Filters\Operator\NotIn;
use Sugarcrm\REST\Endpoint\Data\Filters\Operator\IsNull;
use Sugarcrm\REST\Endpoint\Data\Filters\Operator\NotNull;
use Sugarcrm\REST\Endpoint\Data\Filters\Operator\LessThan;
use Sugarcrm\REST\Endpoint\Data\Filters\Operator\LessThanOrEqual;
use Sugarcrm\REST\Endpoint\Data\Filters\Operator\GreaterThan;
use Sugarcrm\REST\Endpoint\Data\Filters\Operator\GreaterThanOrEqual;
use Sugarcrm\REST\Endpoint\Data\Filters\Operator\Between;
use Sugarcrm\REST\Endpoint\Data\Filters\Operator\DateBetween;
use Sugarcrm\REST\Endpoint\Data\Filters\FilterInterface;
use Sugarcrm\REST\Exception\Filter\UnknownFilterOperator;

/**
 * The default expression implementation provides an API for acess to all Sugar filter operators
 * @package Sugarcrm\REST\Endpoint\Data\Filters\Expression
 * @method AndExpression        and()
 * @method OrExpression         or()
 * @method DateExpression       date($field)
 * @method $this                equals($field,$value)
 * @method $this                notEquals($field,$value)
 * @method $this                starts($field,$value)
 * @method $this                ends($field,$value)
 * @method $this                contains($field,$value)
 * @method $this                in($field,array $value)
 * @method $this                notIn($field,array $value)
 * @method $this                isNull($field)
 * @method $this                notNull($field)
 * @method $this                lt($field,$value)
 * @method $this                lessThan($field,$value)
 * @method $this                lte($field,$value)
 * @method $this                lessThanOrEqualTo($field,$value)
 * @method $this                lessThanOrEquals($field,$value)
 * @method $this                greaterThan($field,$value)
 * @method $this                gte($field,$value)
 * @method $this                greaterThanOrEqualTo($field,$value)
 * @method $this                greaterThanOrEquals($field,$value)
 * @method $this                between($field,$value)
 * @method $this                dateBetween($field,$value)
 */
abstract class AbstractExpression implements FilterInterface, ExpressionInterface
{
    /**
     * @var array
     */
    protected $filters = [];

    /**
     * @var AbstractExpression
     */
    private $parentExpression;

    /**
     * @var array
     */
    protected $operators = [
        'equals' => Equals::class,
        'notEquals' => NotEquals::class,
        'starts' => Starts::class,
        'ends' => Ends::class,
        'contains' => Contains::class,
        'in' => In::class,
        'notIn' => NotIn::class,
        'isNull' => IsNull::class,
        'notNull' => NotNull::class,
        'lt' => LessThan::class,
        'lessThan' => LessThan::class,
        'lte' => LessThanOrEqual::class,
        'lessThanOrEqualTo' => LessThanOrEqual::class,
        'lessThanOrEquals' => LessThanOrEqual::class,
        'gt' => GreaterThan::class,
        'greaterThan' => GreaterThan::class,
        'gte' => GreaterThanOrEqual::class,
        'greaterThanOrEqualTo' => GreaterThanOrEqual::class,
        'greaterThanOrEquals' => GreaterThanOrEqual::class,
        'between' => Between::class,
        'dateBetween' => DateBetween::class,
    ];

    /**
     * @var array
     */
    protected $expressions = [
        'and' => AndExpression::class,
        'or' => OrExpression::class,
        'date' => DateExpression::class,
    ];

    /**
     * @param $name
     * @param $arguments
     * @return AbstractExpression
     * @throws UnknownFilterOperator
     */
    public function __call($name, $arguments)
    {
        if (array_key_exists($name, $this->operators)) {
            $Operator = $this->operators[$name];
            $Op = new $Operator($arguments);
            $this->filters[] = $Op;
            return $this;
        }

        if (array_key_exists($name, $this->expressions)) {
            $Expression =  $this->expressions[$name];
            $Exp = new $Expression($arguments);
            $Exp->setParentExpression($this);
            $this->filters[] = $Exp;
            return $Exp;
        }

        throw new UnknownFilterOperator([$name]);
    }

    /**
     * Sets Parent Expression to allow for nested tree structure
     * @return $this
     */
    public function setParentExpression(AbstractExpression $Expression)
    {
        $this->parentExpression = $Expression;
        return $this;
    }

    /**
     * Gets the Parent Expression of current Expression
     */
    public function getParentExpression(): AbstractExpression
    {
        return $this->parentExpression;
    }

    /**
     * Compiles the Expression based on the stored Filters array
     */
    public function compile(): array
    {
        $data = [];
        foreach ($this->filters as $filter) {
            $data[] = $filter->compile();
        }

        return $data;
    }

    /**
     * @inheritDoc
     */
    public function clear()
    {
        $this->filters = [];
        return $this;
    }
}
