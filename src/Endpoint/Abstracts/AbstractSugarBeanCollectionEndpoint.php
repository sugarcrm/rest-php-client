<?php
/**
 * ©[2016] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace Sugarcrm\REST\Endpoint\Abstracts;

use MRussell\REST\Endpoint\JSON\CollectionEndpoint;

abstract class AbstractSugarBeanCollectionEndpoint extends CollectionEndpoint
{
    protected static $_DEFAULT_PROPERTIES = array(
        'auth' => TRUE,
        'data' => array(
            'required' => array(),
            'defaults' => array()
        )
    );

}