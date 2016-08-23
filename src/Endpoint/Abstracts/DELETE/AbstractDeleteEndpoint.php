<?php
/**
 * ©[2016] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace SugarAPI\SDK\Endpoint\Abstracts\DELETE;

use SugarAPI\SDK\Endpoint\Abstracts\AbstractEndpoint;
use SugarAPI\SDK\Request\DELETE;
use SugarAPI\SDK\Response\JSON;

abstract class AbstractDeleteEndpoint extends AbstractEndpoint
{
    public function __construct($url, array $options = array())
    {
        $this->setRequest(new DELETE());
        $this->setResponse(new JSON($this->getRequest()->getCurlObject()));
        parent::__construct($url, $options);
    }
}
