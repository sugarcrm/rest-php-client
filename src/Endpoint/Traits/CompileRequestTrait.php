<?php

namespace Sugarcrm\REST\Endpoint\Traits;

use Sugarcrm\REST\Endpoint\SugarEndpointInterface;

trait CompileRequestTrait
{
    /**
     * @implements SugarEndpointInterface
     */
    public function compileRequest() {
        return $this->buildRequest();
    }
}