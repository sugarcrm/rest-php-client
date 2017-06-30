<?php
/**
 * ©[2017] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
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
    const COUNT_OPTION = 'count';

    protected static $_ENDPOINT_URL = '$module/filter/$:count';

    /**
     * @var FilterData
     */
    protected $Filter;

    /**
     * @var EndpointData
     */
    protected $data;

    /**
     * @var
     */
    protected $totalCount;

    /**
     * Sanitize passed in options
     * @param array $options
     * @return $this|mixed
     */
    public function setOptions(array $options)
    {
        if (isset($options[1])){
            unset($options[1]);
            $options[self::COUNT_OPTION] = self::COUNT_OPTION;
        }
        return parent::setOptions($options);
    }

    /**
     * @inheritdoc
     */
    public function fetch(){
        $this->setProperty(self::PROPERTY_HTTP_METHOD,JSON::HTTP_GET);
        return parent::fetch();
    }

    /**
     * If Filter Options is configured, use Filter Object to update Data
     * @inheritdoc
     */
    protected function configureData($data)
    {
        if (is_object($this->Filter)){
            $compiledFilter = $this->Filter->asArray();
            if (!empty($compiledFilter)){
                $data->update(array(FilterData::FILTER_PARAM => $this->Filter->asArray()));
            }
        }
        return parent::configureData($data);
    }

    /**
     * Configure the Filter Parameters for the Filter API
     * @param bool $reset
     * @return FilterData
     */
    public function filter($reset = FALSE)
    {
        $this->setProperty(self::PROPERTY_HTTP_METHOD,JSON::HTTP_POST);
        if (empty($this->Filter)){
            $this->Filter = new FilterData();
            $this->Filter->setEndpoint($this);
            $data = $this->getData();
            if (isset($data[FilterData::FILTER_PARAM]) && !empty($data[FilterData::FILTER_PARAM])){
                $this->Filter->update($data[FilterData::FILTER_PARAM]);
            }
        }
        if ($reset){
            $this->Filter->reset();
            $data = $this->getData();
            if (isset($data[FilterData::FILTER_PARAM]) && !empty($data[FilterData::FILTER_PARAM])){
                unset($data[FilterData::FILTER_PARAM]);
                $this->setData($data);
            }
        }
        return $this->Filter;
    }

    /**
     * Configure the Request to use Count Endpoint
     */
    public function count()
    {
        $this->setOptions(array($this->getModule(),self::COUNT_OPTION));
        return $this->execute();
    }

}