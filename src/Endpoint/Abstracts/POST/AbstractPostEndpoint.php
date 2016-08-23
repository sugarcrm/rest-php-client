<?php
/**
 * ©[2016] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace SugarAPI\SDK\Endpoint\Abstracts\POST;

use SugarAPI\SDK\Endpoint\Abstracts\AbstractEndpoint;
use SugarAPI\SDK\Request\POST;
use SugarAPI\SDK\Response\JSON;

abstract class AbstractPostEndpoint extends AbstractEndpoint
{
    public function __construct($url, array $options = array())
    {
        $this->setRequest(new POST());
        $this->setResponse(new JSON($this->Request->getCurlObject()));
        parent::__construct($url, $options);
    }
}
