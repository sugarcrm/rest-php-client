<?php
/**
 * ©[2016] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace SugarAPI\SDK\Endpoint\Abstracts\POST;

use SugarAPI\SDK\Endpoint\Abstracts\AbstractEndpoint;
use SugarAPI\SDK\Request\POSTFile;
use SugarAPI\SDK\Response\JSON;

abstract class AbstractPostFileEndpoint extends AbstractEndpoint
{
    public function __construct($url, array $options = array())
    {
        $this->setRequest(new POSTFile());
        $this->setResponse(new JSON($this->Request->getCurlObject()));
        parent::__construct($url, $options);
    }
}
