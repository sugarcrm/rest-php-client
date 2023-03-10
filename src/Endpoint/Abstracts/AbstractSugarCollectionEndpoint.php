<?php
/**
 * ©[2022] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace Sugarcrm\REST\Endpoint\Abstracts;

use GuzzleHttp\Psr7\Response;
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
    use CompileRequestTrait;
    use PsrLoggerTrait;

    public const SUGAR_OFFSET_PROPERTY = 'offset';

    public const SUGAR_LIMIT_PROPERTY = 'max_num';

    public const SUGAR_COLLECTION_RESP_PROP = 'records';

    public const PROPERTY_SUGAR_DEFAULT_LIMIT = 'default_limit';

    protected static $_DEFAULT_LIMIT = 50;

    protected static $_RESPONSE_PROP = self::SUGAR_COLLECTION_RESP_PROP;

    /**
     * Current record offset to query for
     * @var int
     */
    protected $_offset = 0;

    /**
     * Max number of records to return
     * @var int
     */
    protected $_max_num;

    /**
     * Next offset to pass
     * @var int
     */
    protected $_next_offset = 0;

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

    public function __construct(array $urlArgs = array(), array $properties = array())
    {
        parent::__construct($urlArgs, $properties);
        $this->_max_num = $this->defaultLimit();
    }

    /**
     * Append Offset and Limit to payload
     * @inheritDoc
     */
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
    public function getOffset()
    {
        return $this->_offset;
    }

    /**
     * Set the record offset to retrieve via API
     * @param $offset
     * @return $this
     */
    public function setOffset($offset)
    {
        $this->_offset = intval($offset);
        return $this;
    }

    /**
     * Get the Limit (max_num) property of the Collection
     * @return int
     */
    public function getLimit()
    {
        return $this->_max_num;
    }

    /**
     * Set the Limit (max_num) property of the Collection
     * @param $limit
     * @return $this
     */
    public function setLimit($limit)
    {
        $this->_max_num = intval($limit);
        return $this;
    }

    /**
     * Get the default Limit set on COllection-
     * @return int|mixed
     */
    protected function defaultLimit()
    {
        return $this->getProperty(self::PROPERTY_SUGAR_DEFAULT_LIMIT) ?? static::$_DEFAULT_LIMIT;
    }

    /**
     * @return AbstractSugarCollectionEndpoint
     */
    public function reset()
    {
        $this->_next_offset = 0;
        $this->_offset = 0;
        $this->_max_num = $this->defaultLimit();
        return parent::reset();
    }

    /**
     * Parse next offset to next_offset property
     * @inheritDoc
     */
    protected function parseResponse(Response $response): void
    {
        if ($response->getStatusCode() == 200) {
            $body = $this->getResponseBody();
            if (isset($body['next_offset'])) {
                $this->_next_offset = intval($body['next_offset']);
            }
        }
        parent::parseResponse($response);
    }

    /**
     * @return $this
     * @throws \MRussell\REST\Exception\Endpoint\InvalidRequest
     */
    public function nextPage()
    {
        if ($this->hasMore()) {
            $this->_offset += $this->_max_num;
            $this->fetch();
        }
        return $this;
    }

    /**
     * @return $this
     * @throws \MRussell\REST\Exception\Endpoint\InvalidRequest
     */
    public function previousPage()
    {
        if ($this->_next_offset > 0) {
            $this->_offset -= $this->_max_num;
            $this->fetch();
        }
        return $this;
    }

    /**
     * Check if collection has more data to load
     * @return bool
     */
    public function hasMore()
    {
        return $this->_next_offset > -1;
    }

    /**
     * Get the next_offset in collection
     * @return int
     */
    public function getNextOffset(): int
    {
        return $this->_next_offset;
    }
}
