<?php
/**
 * ©[2016] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace Sugarcrm\REST\Endpoint;

use MRussell\Http\Request\JSON;
use MRussell\REST\Endpoint\Data\EndpointData;
use Sugarcrm\REST\Endpoint\Abstracts\AbstractSugarBeanCollectionEndpoint;
use Sugarcrm\REST\Endpoint\Data\FilterData;

/**
 * Class ModuleFilter
 * @package Sugarcrm\REST\Endpoint
 */
class ModuleFilter extends AbstractSugarBeanCollectionEndpoint
{
    const FILTER_PARAM = 'filter';

    protected static $_MODEL_CLASS = 'Sugarcrm\\REST\\Endpoint\\Module';

    protected static $_ENDPOINT_URL = '$module/$:filter';

    /**
     * @var FilterData
     */
    protected $Filter;

    /**
     * The SugarCRM Module being used
     * @var string
     */
    protected $module;

    /**
     * @var EndpointData
     */
    protected $data;

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
    public function fetch(){
        if (isset($this->options[self::FILTER_PARAM])){
            unset($this->options[self::FILTER_PARAM]);
        }
        return parent::fetch();
    }

    /**
     * Configure the Filter Parameters for the Filter API
     * @param bool $reset
     * @return FilterData
     */
    public function filter($reset = FALSE){
        $this->options[self::FILTER_PARAM] = self::FILTER_PARAM;
        $this->setProperty('httpMethod',JSON::HTTP_POST);
        if (empty($this->Filter)){
            $this->Filter = new FilterData();
            $this->Filter->setEndpoint($this);
        }
        if ($reset){
            $this->Filter->reset();
        }
        return $this->Filter;
    }

}