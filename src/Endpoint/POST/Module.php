<?php
/**
 * ©[2016] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace SugarAPI\SDK\Endpoint\POST;

use SugarAPI\SDK\Endpoint\Abstracts\POST\AbstractPostEndpoint;

class Module extends AbstractPostEndpoint
{
    /**
     * @inheritdoc
     */
    protected $_URL = '$module';
}
