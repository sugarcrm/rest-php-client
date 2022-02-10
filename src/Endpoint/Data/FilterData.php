<?php

/**
 * ©[2019] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
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
    use ArrayObjectAttributesTrait, PropertiesTrait, GetAttributesTrait, SetAttributesTrait, ClearAttributesTrait;

    const FILTER_PARAM = 'filter';

    /**
     * @var AbstractSmartEndpoint
     */
    private $Endpoint;


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
     * @param AbstractSmartEndpoint $Endpoint
     * @return self
     */
    public function setEndpoint(AbstractSmartEndpoint $Endpoint): FilterData {
        $this->Endpoint = $Endpoint;
        return $this;
    }

    /**
     * Return the Endpoint being used with the Filter Data
     * @return AbstractSmartEndpoint
     * @codeCoverageIgnore
     */
    public function getEndpoint(): AbstractSmartEndpoint {
        return $this->Endpoint;
    }

    /**
     * @return AbstractSmartEndpoint|false
     * @throws \MRussell\REST\Exception\Endpoint\InvalidRequest
     */
    public function execute() {
        if (isset($this->Endpoint)) {
            return $this->Endpoint->execute($this->toArray());
        }
        return false;
    }
}
