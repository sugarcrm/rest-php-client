<?php
/**
 * ©[2016] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace SugarAPI\SDK\Endpoint\GET;

use SugarAPI\SDK\Endpoint\Abstracts\GET\AbstractGetFileEndpoint;

class ModuleRecordFileField extends AbstractGetFileEndpoint
{
    /**
     * @inheritdoc
     */
    protected $_URL = '$module/$record/file/$field';
}
