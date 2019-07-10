<?php
/**
 * ©[2019] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace Sugarcrm\REST\Endpoint\Data\Filters\Operator;


class Between extends AbstractOperator
{
    const OPERATOR = '$between';

    protected static $_OPERATOR = self::OPERATOR;

}