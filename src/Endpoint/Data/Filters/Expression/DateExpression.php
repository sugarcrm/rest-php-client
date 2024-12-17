<?php

/**
 * ©[2024] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace Sugarcrm\REST\Endpoint\Data\Filters\Expression;

use Sugarcrm\REST\Endpoint\Data\Filters\Operator\Equals;
use Sugarcrm\REST\Endpoint\Data\Filters\Operator\NotEquals;
use Sugarcrm\REST\Endpoint\Data\Filters\Operator\IsNull;
use Sugarcrm\REST\Endpoint\Data\Filters\Operator\NotNull;
use Sugarcrm\REST\Endpoint\Data\Filters\Operator\LessThan;
use Sugarcrm\REST\Endpoint\Data\Filters\Operator\LessThanOrEqual;
use Sugarcrm\REST\Endpoint\Data\Filters\Operator\GreaterThan;
use Sugarcrm\REST\Endpoint\Data\Filters\Operator\GreaterThanOrEqual;
use Sugarcrm\REST\Endpoint\Data\Filters\Operator\DateBetween;
use Sugarcrm\REST\Endpoint\Data\Filters\Operator\DateRange;
use Sugarcrm\REST\Exception\Filter\MissingFieldForDateExpression;
use Sugarcrm\REST\Exception\Filter\UnknownFilterOperator;

/**
 * Date Expression provides a wrapper for manging date field specific filters
 * @package Sugarcrm\REST\Endpoint\Data\Filters\Expression
 * @method $this                yesterday()
 * @method $this                today()
 * @method $this                tomorrow()
 * @method $this                last7Days()
 * @method $this                next7Days()
 * @method $this                last30days()
 * @method $this                next30Days()
 * @method $this                lastMonth()
 * @method $this                thisMonth()
 * @method $this                nextMonth()
 * @method $this                lastYear()
 * @method $this                thisYear()
 * @method $this                nextYear()
 * @method $this                equals($value)
 * @method $this                notEquals($value)
 * @method $this                isNull()
 * @method $this                notNull()
 * @method $this                lt($value)
 * @method $this                lessThan($value)
 * @method $this                lte($value)
 * @method $this                lessThanOrEqualTo($value)
 * @method $this                lessThanOrEquals($value)
 * @method $this                greaterThan($value)
 * @method $this                gte($value)
 * @method $this                greaterThanOrEqualTo($value)
 * @method $this                greaterThanOrEquals($value)
 * @method $this                between($value)
 */
class DateExpression extends AbstractExpression
{
    public const OPERATOR = '';

    protected $dateField;

    protected $ranges = [
        'yesterday' => 'yesterday',
        'today' => 'today',
        'tomorrow' => 'tomorrow',
        'last7Days' => 'last_7_days',
        'next7Days' => 'next_7_days',
        'last30days' => 'last_30_days',
        'next30Days' => 'next_30_days',
        'lastMonth' => 'last_month',
        'thisMonth' => 'this_month',
        'nextMonth' => 'next_month',
        'lastYear' => 'last_year',
        'thisYear' => 'this_year',
        'nextYear' => 'next_year',
    ];

    /**
     * @var array
     */
    protected $operators = [
        'equals' => Equals::class,
        'notEquals' => NotEquals::class,
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
        'dateBetween' => DateBetween::class,
        'between' => DateBetween::class,
    ];

    /**
     * @var array
     */
    protected $expressions = [];

    /**
     * DateExpression constructor.
     * @param array $arguments
     */
    public function __construct($arguments = [])
    {
        if (isset($arguments[0])) {
            $this->field($arguments[0]);
        }
    }

    /**
     * Set the field that date expression is against
     * @param $field
     * @return $this
     */
    public function field($field)
    {
        $this->dateField = $field;
        return $this;
    }

    public function __call($name, $arguments)
    {
        if (empty($this->dateField)) {
            throw new MissingFieldForDateExpression();
        }

        $args = [$this->dateField];
        if (array_key_exists($name, $this->ranges)) {
            $range = $this->ranges[$name];
            $args[] = $range;
            $Op = new DateRange($args);
            $this->filters[0] = $Op;
            return $this;
        }

        if (array_key_exists($name, $this->operators)) {
            $args = array_merge($args, $arguments);
            $Operator = $this->operators[$name];
            $O = new $Operator($args);
            $this->filters[0] = $O;
            return $this;
        }

        throw new UnknownFilterOperator([$name]);
    }

    /**
     * @inheritDoc
     */
    public function compile(): array
    {
        if (isset($this->filters[0])) {
            return $this->filters[0]->compile();
        }

        return [];
    }

    /**
     * Human Friendly Expression End, allow you to traverse back up the Filter expression
     * @return AbstractExpression
     * @codeCoverageIgnore
     */
    public function endDate()
    {
        return $this->getParentExpression();
    }
}
