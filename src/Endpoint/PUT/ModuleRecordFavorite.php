<?php
/**
 * ©[2016] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace SugarAPI\SDK\Endpoint\PUT;

use SugarAPI\SDK\Endpoint\Abstracts\PUT\AbstractPutEndpoint;

class ModuleRecordFavorite extends AbstractPutEndpoint
{
    /**
     * @inheritdoc
     */
    protected $_URL = '$module/$record/favorite';
}
