<?php
/**
 * ©[2022] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace Sugarcrm\REST\Endpoint\Data\Filters\Operator;

class In extends AbstractOperator
{
    const OPERATOR = '$in';

    protected static $_OPERATOR = self::OPERATOR;

}