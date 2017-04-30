<?php
/**
 * ©[2016] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace Sugarcrm\REST\Endpoint\Abstracts;

use MRussell\REST\Endpoint\JSON\CollectionEndpoint;
use Sugarcrm\REST\Endpoint\SugarEndpointInterface;

/**
 * Class AbstractSugarBeanCollectionEndpoint
 * @package Sugarcrm\REST\Endpoint\Abstracts
 */
abstract class AbstractSugarBeanCollectionEndpoint extends CollectionEndpoint implements SugarEndpointInterface
{
    /**
     * @inehritdoc
     */
    protected static $_DEFAULT_PROPERTIES = array(
        'auth' => TRUE,
        'data' => array(
            'required' => array(),
            'defaults' => array()
        )
    );

    /**
     * @inheritdoc
     * @codeCoverageIgnore
     */
    public function compileRequest(){
        return $this->configureRequest($this->getRequest());
    }
}