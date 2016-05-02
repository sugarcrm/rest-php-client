<?php
/**
 * ©[2016] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace SugarAPI\SDK\EntryPoint\DELETE;

use SugarAPI\SDK\EntryPoint\Abstracts\DELETE\AbstractDeleteEntryPoint;

class ModuleRecordFileField extends AbstractDeleteEntryPoint {

    /**
     * @inheritdoc
     */
    protected $_URL = '$module/$record/file/$field';

}