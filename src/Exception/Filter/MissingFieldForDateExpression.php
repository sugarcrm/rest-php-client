<?php
/**
 * ©[2022] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace Sugarcrm\REST\Exception\Filter;


use MRussell\REST\Exception\Endpoint\EndpointException;

/**
 * @package Sugarcrm\REST\Exception\Filter
 */
class MissingFieldForDateExpression extends EndpointException
{
    protected $message = 'Field not configured on DateExpression';
}