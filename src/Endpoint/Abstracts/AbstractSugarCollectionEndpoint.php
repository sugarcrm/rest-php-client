<?php
/**
 * ©[2022] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace Sugarcrm\REST\Endpoint\Abstracts;

use MRussell\REST\Endpoint\Data\AbstractEndpointData;
use MRussell\REST\Endpoint\CollectionEndpoint;
use MRussell\REST\Traits\PsrLoggerTrait;
use Sugarcrm\REST\Endpoint\SugarEndpointInterface;
use Sugarcrm\REST\Endpoint\Traits\CompileRequestTrait;

/**
 * Provides access to a multi-bean collection retrieved from Sugar 7 REST Api
 * - Built in pagination functionality
 * @package Sugarcrm\REST\Endpoint\Abstracts
 */
abstract class AbstractSugarCollectionEndpoint extends CollectionEndpoint implements SugarEndpointInterface
{
    use CompileRequestTrait, PsrLoggerTrait;

    const SUGAR_OFFSET_PROPERTY = 'offset';

    const SUGAR_LIMIT_PROPERTY = 'max_num';

    const SUGAR_COLLECTION_RESP_PROP = 'records';

    protected static $_RESPONSE_PROP = self::SUGAR_COLLECTION_RESP_PROP;

    protected $offset = 0;

    protected $max_num = 20;

    /**
     * @inehritdoc
     */
    protected static $_DEFAULT_PROPERTIES = array(
        self::PROPERTY_AUTH => true,
        self::PROPERTY_DATA => array(
            AbstractEndpointData::DATA_PROPERTY_REQUIRED => array(),
            AbstractEndpointData::DATA_PROPERTY_DEFAULTS => array()
        )
    );

    protected function configurePayload()
    {
        $data = parent::configurePayload();
        $data[self::SUGAR_OFFSET_PROPERTY] = $this->getOffset();
        $data[self::SUGAR_LIMIT_PROPERTY] = $this->getLimit();
        return $data;
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