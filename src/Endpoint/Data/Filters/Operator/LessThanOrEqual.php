<?php
/**
 * ©[2019] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace Sugarcrm\REST\Endpoint\Data\Filters\Operator;


class LessThanOrEqual extends AbstractOperator
{
    const OPERATOR = '$lte';

    protected static $_OPERATOR = self::OPERATOR;

}