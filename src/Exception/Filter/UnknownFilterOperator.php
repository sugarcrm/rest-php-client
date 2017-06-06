<?php

namespace Sugarcrm\REST\Exception\Filter;

use MRussell\REST\Exception\Endpoint\EndpointException;

class UnknownFilterOperator extends EndpointException
{
    protected $message = 'Unknown Filter Operator: %s';
}