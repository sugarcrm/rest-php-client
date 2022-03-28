<?php
/**
 * ©[2022] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

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