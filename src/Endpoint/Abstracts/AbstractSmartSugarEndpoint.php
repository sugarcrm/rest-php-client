<?php
/**
 * ©[2017] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace Sugarcrm\REST\Endpoint\Abstracts;

use MRussell\REST\Endpoint\JSON\SmartEndpoint;
use Sugarcrm\REST\Endpoint\SugarEndpointInterface;

/**
 * Class AbstractSmartSugarEndpoint
 * @package Sugarcrm\REST\Endpoint\Abstracts
 */
class AbstractSmartSugarEndpoint extends SmartEndpoint implements SugarEndpointInterface
{
    /**
     * @inheritdoc
     * @codeCoverageIgnore
     */
    public function compileRequest(){
        return $this->configureRequest($this->getRequest());
    }
}