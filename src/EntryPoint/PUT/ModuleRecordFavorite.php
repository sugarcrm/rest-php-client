<?php
/**
 * ©[2016] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace SugarAPI\SDK\EntryPoint\PUT;

use SugarAPI\SDK\EntryPoint\Abstracts\PUT\AbstractPutEntryPoint;

class ModuleRecordFavorite extends AbstractPutEntryPoint {

    /**
     * @inheritdoc
     */
    protected $_URL = '$module/$record/favorite';

}