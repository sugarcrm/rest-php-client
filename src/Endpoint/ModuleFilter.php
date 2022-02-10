<?php

/**
 * ©[2019] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
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
     * @param array $args
     * @return $this|mixed
     */
    public function setUrlArgs(array $args): EndpointInterface {
        if (isset($args[1])) {
            unset($args[1]);
            $args[self::COUNT_OPTION] = self::COUNT_OPTION;
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
        if (is_object($this->Filter)) {
            // Important to compile the filters
            // FIXME: mrussell to review
            // In the meeting it felt like it was not failing but after couple more test I found 
            // out disabling it causes Sugarcrm\REST\Tests\Endpoint\Data\BulkRequestTest::testAsArray 
            // to fail. 
            $compiledFilter = $this->Filter->compile();
            
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
    public function filter($reset = true) {
        $this->setProperty(self::PROPERTY_HTTP_METHOD, "POST");
        if (empty($this->Filter)) {
            $this->Filter = new FilterData();
            $this->Filter->setEndpoint($this);
            $data = $this->getData()->toArray();
            if (isset($data[FilterData::FILTER_PARAM]) && !empty($data[FilterData::FILTER_PARAM])) {
                $this->Filter->set($data[FilterData::FILTER_PARAM]);
            }
        }
        if ($reset) {
            $this->Filter->reset();
            $data = $this->getData()->toArray();
            if (isset($data[FilterData::FILTER_PARAM]) && !empty($data[FilterData::FILTER_PARAM])) {
                unset($data[FilterData::FILTER_PARAM]);
                $this->setData($data);
            }
        }
        return $this->Filter;
    }
    

    /**
     * Configure the Request to use Count Endpoint
     */
    public function count() {
        $this->setUrlArgs(array($this->getModule(), self::COUNT_OPTION));
        $this->execute();
        $this->setUrlArgs(array($this->getModule()));
        return $this;
    }

}