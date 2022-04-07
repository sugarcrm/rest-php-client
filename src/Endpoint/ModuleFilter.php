<?php

/**
 * ©[2022] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace Sugarcrm\REST\Endpoint;

use ArrayAccess;
use GuzzleHttp\Psr7\Response;
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

    protected static $_ENDPOINT_URL = '$module/filter/$:count';

    /**
     * @var FilterData
     */
    protected $filter;

    /**
     * @var int
     */
    protected $_totalCount;

    /**
     * @var bool
     */
    private $_count = false;

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
     * @param array $urlArgs
     * @return string
     */
    protected function configureURL(array $urlArgs): string
    {
        if ($this->_count){
            $urlArgs[self::ARG_COUNT] = self::ARG_COUNT;
        }
        return parent::configureURL($urlArgs);
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

    public function parseResponse(Response $response): void
    {
        if ($this->_count){
            if ($response->getStatusCode() == 200){
                $body = $this->getResponseBody();
                if (isset($body['record_count'])){
                    $this->_totalCount = intval($body['record_count']);
                }
            }
            $this->_count = false;
        }
        parent::parseResponse($response);
    }


    /**
     * Configure the Request to use Count Endpoint
     */
    public function count() {
        $this->_count = true;
        $this->execute();
        return $this;
    }

    /**
     * Get the total count
     * @return int
     */
    public function getTotalCount()
    {
        return $this->_totalCount;
    }

}