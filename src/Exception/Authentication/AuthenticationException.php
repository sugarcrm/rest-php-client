<?php
/**
 * ©[2016] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace SugarAPI\SDK\Exception\Authentication;

use SugarAPI\SDK\Exception\SDKException;

class AuthenticationException extends SDKException
{
    protected $message = 'Authentication Exception [%s] occurred in SDK Client. Message: %s';

    public function __construct($message)
    {
        parent::__construct(sprintf($this->message, get_called_class(), $message));
    }
}
