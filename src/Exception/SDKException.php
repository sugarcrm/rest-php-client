<?php
/**
 * ©[2016] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace SugarAPI\SDK\Exception;

class SDKException extends \Exception
{
    protected $default_message = 'Unknown SDK Exception occurred.';

    public function __construct($message = '')
    {
        if (empty($message)) {
            $message = $this->default_message;
        }
        parent::__construct($message);
    }
}
