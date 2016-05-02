<?php
/**
 * ©[2016] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace SugarAPI\SDK\EntryPoint\POST;


use SugarAPI\SDK\EntryPoint\Abstracts\POST\AbstractPostEntryPoint;

class ModuleRecordRelationship extends AbstractPostEntryPoint {

    /**
     * @inheritdoc
     */
    protected $_URL = '$module/$record/link/$relationship';

}