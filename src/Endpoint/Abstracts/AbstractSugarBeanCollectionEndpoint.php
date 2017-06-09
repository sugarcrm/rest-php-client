<?php
/**
 * ©[2016] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace Sugarcrm\REST\Endpoint\Abstracts;

/**
 * Class AbstractSugarBeanCollectionEndpoint
 * @package Sugarcrm\REST\Endpoint\Abstracts
 */
abstract class AbstractSugarBeanCollectionEndpoint extends AbstractSugarCollectionEndpoint
{
    /**
     * The SugarCRM Module being used
     * @var string
     */
    protected $module;

    public function setOptions(array $options) {
        $opts = array();
        if (isset($options[0])){
            $this->setModule($options[0]);
            $opts['module'] = $this->module;
        }
        return parent::setOptions($opts);
    }

    /**
     * Set the Sugar Module currently being used
     * @param $module
     * @return $this
     */
    public function setModule($module){
        $this->module = $module;
        return $this;
    }

    /**
     * Get the Sugar Module currently configured
     * @return mixed
     */
    public function getModule(){
        return $this->module;
    }

    /**
     * @inheritdoc
     */
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