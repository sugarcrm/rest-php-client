<?php
/**
 * ©[2016] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace SugarAPI\SDK\Exception\Endpoint;

use SugarAPI\SDK\Exception\SDKException;

class EndpointException extends SDKException
{
    protected $message = 'Endpoint Exception [%s] occurred on Endpoint %s: %s';

    public function __construct($Endpoint, $data)
    {
        parent::__construct(sprintf($this->message, get_called_class(), $Endpoint, $data));
    }
}
