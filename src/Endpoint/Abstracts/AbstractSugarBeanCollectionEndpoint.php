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

    public function updateCollection()
    {
        $responseBody = $this->Response->getBody();
        if (!empty($responseBody['records'])){
            if (isset($this->model)){
                $modelIdKey = $this->buildModel()->modelIdKey();
                foreach($responseBody['records'] as $key => $model){
                    if (isset($model[$modelIdKey])){
                        $this->collection[$model[$modelIdKey]] = $model;
                    } else {
                        $this->collection[] = $model;
                    }
                }
            } else {
                $this->collection = $responseBody['records'];
            }
        }
    }
}