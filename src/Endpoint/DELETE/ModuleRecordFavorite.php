<?php
/**
 * ©[2016] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace SugarAPI\SDK\Endpoint\DELETE;

use SugarAPI\SDK\Endpoint\Abstracts\DELETE\AbstractDeleteEndpoint;

class ModuleRecordFavorite extends AbstractDeleteEndpoint
{
    protected $_URL = '$module/$record/favorite';
}
