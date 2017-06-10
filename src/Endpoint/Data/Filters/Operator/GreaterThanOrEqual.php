<?php
/**
 * ©[2017] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace Sugarcrm\REST\Endpoint\Data\Filters\Operator;


class GreaterThanOrEqual extends AbstractOperator
{
    const OPERATOR = '$gte';

    protected static $_OPERATOR = self::OPERATOR;

}