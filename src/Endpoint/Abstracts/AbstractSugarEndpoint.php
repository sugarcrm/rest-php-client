<?php
/**
 * ©[2019] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace Sugarcrm\REST\Endpoint\Abstracts;

use MRussell\REST\Endpoint\Endpoint;
use Sugarcrm\REST\Endpoint\SugarEndpointInterface;

/**
 * Base Sugar API Endpoint for the simplest of REST functionality
 * @package Sugarcrm\REST\Endpoint\Abstracts
 */
abstract class AbstractSugarEndpoint extends Endpoint implements SugarEndpointInterface
{
    /**
     * @inheritdoc
     * @codeCoverageIgnore
     */
    public function compileRequest(){
        return $this->buildRequest();
        // return $this->configureRequest($this->getRequest());
    }
}