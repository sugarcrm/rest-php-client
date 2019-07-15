<?php
/**
 * ©[2019] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace Sugarcrm\REST\Endpoint\Abstracts;

use MRussell\REST\Endpoint\Data\EndpointData;
use MRussell\REST\Endpoint\JSON\CollectionEndpoint;
use Sugarcrm\REST\Endpoint\SugarEndpointInterface;

/**
 * Provides access to a multi-bean collection retrieved from Sugar 7 REST Api
 * - Built in pagination functionality
 * @package Sugarcrm\REST\Endpoint\Abstracts
 */
abstract class AbstractSugarCollectionEndpoint extends CollectionEndpoint implements SugarEndpointInterface
{
    const SUGAR_OFFSET_PROPERTY = 'offset';

    const SUGAR_LIMIT_PROPERTY = 'max_num';

    protected $offset = 0;

    protected $max_num = 20;

    /**
     * @inehritdoc
     */
    protected static $_DEFAULT_PROPERTIES = array(
        self::PROPERTY_AUTH => TRUE,
        self::PROPERTY_DATA => array(
            EndpointData::DATA_PROPERTY_REQUIRED => array(),
            EndpointData::DATA_PROPERTY_DEFAULTS => array()
        )
    );

    protected function configureData($data)
    {
        $data[self::SUGAR_OFFSET_PROPERTY] = $this->getOffset();
        $data[self::SUGAR_LIMIT_PROPERTY] = $this->getLimit();
        return parent::configureData($data);
    }

    /**
     * @inheritdoc
     * @codeCoverageIgnore
     */
    public function compileRequest(){
        return $this->configureRequest($this->getRequest());
    }

    /**
     * Get the configured offset
     * @return int
     */
    public function getOffset(){
        return $this->offset;
    }

    /**
     * Set the record offset to retrieve via API
     * @param $offset
     * @return $this
     */
    public function setOffset($offset){
        $this->offset = intval($offset);
        return $this;
    }

    /**
     * Get the Limit (max_num) property of the Collection
     * @return int
     */
    public function getLimit(){
        return $this->max_num;
    }

    /**
     * Set the Limit (max_num) property of the Collection
     * @param $limit
     * @return $this
     */
    public function setLimit($limit){
        $this->max_num = intval($limit);
        return $this;
    }
}