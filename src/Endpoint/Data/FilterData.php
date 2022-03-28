<?php

/**
 * ©[2022] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace Sugarcrm\REST\Endpoint\Data;

use MRussell\REST\Endpoint\Abstracts\AbstractSmartEndpoint;
use MRussell\REST\Endpoint\Data\AbstractEndpointData;
use MRussell\REST\Endpoint\Data\DataInterface;
use MRussell\REST\Endpoint\Traits\ArrayObjectAttributesTrait;
use MRussell\REST\Endpoint\Traits\ClearAttributesTrait;
use MRussell\REST\Endpoint\Traits\GetAttributesTrait;
use MRussell\REST\Endpoint\Traits\PropertiesTrait;
use MRussell\REST\Endpoint\Traits\SetAttributesTrait;
use Sugarcrm\REST\Endpoint\Data\Filters\Expression\AbstractExpression;

/**
 * Class FilterData
 * @package Sugarcrm\REST\Endpoint\Data
 */
class FilterData extends AbstractExpression implements DataInterface {
    use PropertiesTrait, GetAttributesTrait, SetAttributesTrait, ClearAttributesTrait;
    use ArrayObjectAttributesTrait {
        toArray as protected attributesArray;
    }

    const FILTER_PARAM = 'filter';

    /**
     * @var AbstractSmartEndpoint
     */
    private $endpoint;


    //Overloads
    public function __construct(AbstractSmartEndpoint $Endpoint = NULL) {
        if ($Endpoint !== NULL) {
            $this->setEndpoint($Endpoint);
        }
    }

    /**
     * Set Data back to Defaults and clear out data
     * @return AbstractEndpointData
     */
    public function reset(): DataInterface {
        $this->filters = [];
        return $this->clear();
    }

    /**
     * Set the Endpoint using the Filter Data
     * @param AbstractSmartEndpoint $endpoint
     * @return self
     */
    public function setEndpoint(AbstractSmartEndpoint $endpoint): FilterData {
        $this->endpoint = $endpoint;
        return $this;
    }

    /**
     * Return the Endpoint being used with the Filter Data
     * @return AbstractSmartEndpoint
     * @codeCoverageIgnore
     */
    public function getEndpoint() {
        return $this->endpoint;
    }

    /**
     * @return AbstractSmartEndpoint|false
     * @throws \MRussell\REST\Exception\Endpoint\InvalidRequest
     */
    public function execute() {
        $endpoint = $this->getEndpoint();
        if ($endpoint) {
            $endpoint->getData()->set([FilterData::FILTER_PARAM => $this->toArray()]);
            return $endpoint->execute();
        }
        return false;
    }

    /**
     * Return the entire Data array
     * @param bool $compile - Whether or not to verify if Required Data is filled in
     * @return array
     */
    public function toArray($compile = TRUE): array {
        if ($compile){
            $data = $this->compile();
            if (!empty($data)){
                $this->attributes = array_replace_recursive($this->attributes,$data);
            }
        }
        return $this->attributes;
    }
}
