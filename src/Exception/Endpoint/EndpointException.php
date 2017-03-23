<?php
/**
 * ©[2016] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace Sugarcrm\REST\Exception\Endpoint;

use Sugarcrm\REST\Exception\SDKException;

class EndpointException extends SDKException
{
    protected $message = 'Endpoint Exception [%s] occurred on Endpoint %s: %s';

    public function __construct($Endpoint, $data)
    {
        parent::__construct(sprintf($this->message, get_called_class(), $Endpoint, $data));
    }
}
