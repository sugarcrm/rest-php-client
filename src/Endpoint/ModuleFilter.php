<?php

/**
 * ©[2022] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace Sugarcrm\REST\Endpoint;

use ArrayAccess;
use MRussell\REST\Endpoint\Abstracts\AbstractCollectionEndpoint;
use MRussell\REST\Endpoint\Data\EndpointData;
use MRussell\REST\Endpoint\Interfaces\CollectionInterface;
use MRussell\REST\Endpoint\Interfaces\EndpointInterface;
use Sugarcrm\REST\Endpoint\Abstracts\AbstractSugarBeanCollectionEndpoint;
use Sugarcrm\REST\Endpoint\Data\FilterData;

/**
 * Provides access to the Filter API for a given Module
 * - Also allows for retrieving counts of filters/records
 * - Works with a single Module Bean type
 * - Provides access to the Filter API and Filter Query Builder
 * - Tracks pagination
 * @package Sugarcrm\REST\Endpoint
 */
class ModuleFilter extends AbstractSugarBeanCollectionEndpoint {
    const ARG_COUNT = 'count';
    const ARG_LEAN_COUNT = 'leancount';

    protected static $_ENDPOINT_URL = '$module/filter/$:count';

    /**
     * @var FilterData
     */
    protected $filter;

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
     * @param array $args
     * @return $this|mixed
     */
    public function setUrlArgs(array $args): EndpointInterface {
        if (isset($args[1])) {
            unset($args[1]);
            $args[self::ARG_COUNT] = self::ARG_COUNT;
        }
        return parent::setUrlArgs($args);
    }

    /**
     * @inheritdoc
     */
    public function fetch(): AbstractCollectionEndpoint {
        $this->setProperty(self::PROPERTY_HTTP_METHOD, "GET");
        return parent::fetch();
    }

    /**
     * If Filter Options is configured, use Filter Object to update Data
     * @inheritdoc
     */
    protected function configurePayload() {
        $data = parent::configurePayload();
        if (is_object($this->filter)) {
            $compiledFilter = $this->filter->compile();
            
            if (!empty($compiledFilter)) {
                $data->set([FilterData::FILTER_PARAM => $compiledFilter]);
            }
        }
        return $data;
    }

    /**
     * Configure the Filter Parameters for the Filter API
     * @param bool $reset
    * @return FilterData
     */
    public function filter(bool $reset = false) {
        $this->setProperty(self::PROPERTY_HTTP_METHOD, "POST");
        if (empty($this->filter)) {
            $this->filter = new FilterData();
            $this->filter->setEndpoint($this);
            $data = $this->getData()->toArray();
            if (isset($data[FilterData::FILTER_PARAM]) && !empty($data[FilterData::FILTER_PARAM])) {
                $this->filter->set($data[FilterData::FILTER_PARAM]);
            }
        }
        if ($reset) {
            $this->filter->reset();
            $data = $this->getData()->toArray();
            if (isset($data[FilterData::FILTER_PARAM]) && !empty($data[FilterData::FILTER_PARAM])) {
                unset($data[FilterData::FILTER_PARAM]);
                $this->setData($data);
            }
        }
        return $this->filter;
    }
    

    /**
     * Configure the Request to use Count Endpoint
     */
    public function count() {
        $this->setUrlArgs(array($this->getModule(), self::ARG_COUNT));
        $this->execute();
        $this->setUrlArgs(array($this->getModule()));
        return $this;
    }

    /**
     * Configure the Request to use Lean Count Endpoint
     */
    public function leanCount() {
        $this->setUrlArgs(array($this->getModule(), self::ARG_LEAN_COUNT));
        $this->execute();
        $this->setUrlArgs(array($this->getModule()));
        return $this;
    }

}