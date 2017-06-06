<?php
/**
 * ©[2016] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace Sugarcrm\REST\Endpoint\Data\Filters\Operator;

class NotNull extends IsNull
{
    const OPERATOR = '$not_null';

    protected static $_OPERATOR = self::OPERATOR;
}