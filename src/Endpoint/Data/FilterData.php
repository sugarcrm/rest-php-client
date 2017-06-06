<?php
/**
 * ©[2016] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace Sugarcrm\REST\Endpoint\Data;

use MRussell\REST\Endpoint\Data\AbstractEndpointData;
use MRussell\REST\Endpoint\Data\DataInterface;
use Sugarcrm\REST\Endpoint\Data\Filters\Expression\AbstractExpression;
use Sugarcrm\REST\Endpoint\ModuleFilter;

/**
 * Class FilterData
 * @package Sugarcrm\REST\Endpoint\Data
 */
class FilterData extends AbstractExpression implements DataInterface
{
    /**
     * @var ModuleFilter
     */
    private $Endpoint;

    /**
     * The array representation of the Data
     * @var array
     */
    private $data = array();

    /**
     * The properties Array that provide useful attributes to internal logic of Data
     * @var array
     */
    protected $properties;

    //Overloads
    public function __construct(ModuleFilter $Endpoint = NULL) {
        if ($Endpoint !== NULL){
            $this->setEndpoint($Endpoint);
        }
    }

    //Array Access
    /**
     * Assigns a value to the specified offset
     * @param string $offset - The offset to assign the value to
     * @param mixed $value - The value to set
     * @abstracting ArrayAccess
     */
    public function offsetSet($offset,$value) {
        if (is_null($offset)) {
            $this->data[] = $value;
        } else {
            $this->data[$offset] = $value;
        }
    }

    /**
     * Whether or not an offset exists
     * @param string $offset - An offset to check for
     * @return boolean
     * @abstracting ArrayAccess
     */
    public function offsetExists($offset) {
        return isset($this->data[$offset]);
    }

    /**
     * Unsets an offset
     * @param string $offset - The offset to unset
     * @abstracting ArrayAccess
     */
    public function offsetUnset($offset) {
        if ($this->offsetExists($offset)) {
            unset($this->data[$offset]);
        }
    }

    /**
     * Returns the value at specified offset
     * @param string $offset - The offset to retrieve
     * @return mixed
     * @abstracting ArrayAccess
     */
    public function offsetGet($offset) {
        return $this->offsetExists($offset) ? $this->data[$offset] : null;
    }

    //Data Interface
    /**
     * Return the entire Data array
     * @param bool $compile - Whether or not to verify if Required Data is filled in
     * @return array
     */
    public function asArray($compile = TRUE){
        if ($compile){
            $data = $this->compile();
            $this->data[ModuleFilter::FILTER_PARAM] = $data;
        }
        return $this->data;
    }

    /**
     * Get the current Data Properties
     * @return array
     */
    public function getProperties() {
        return $this->properties;
    }

    /**
     * Set properties for data
     * @param array $properties
     * @return $this
     */
    public function setProperties(array $properties) {
        $this->properties = $properties;
        return $this;
    }

    /**
     * Set Data back to Defaults and clear out data
     * @return AbstractEndpointData
     */
    public function reset(){
        return $this->clear();
    }

    /**
     * Clear out data array
     * @return $this
     */
    public function clear(){
        $this->data = array();
        parent::clear();
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function update(array $data){
        foreach($data as $key => $value){
            $this->data[$key] = $value;
        }
        return $this;
    }

    /**
     * @param ModuleFilter $Endpoint
     * @return self
     */
    public function setEndpoint(ModuleFilter $Endpoint){
        $this->Endpoint = $Endpoint;
        return $this;
    }

    /**
     * @return ModuleFilter|false
     * @throws \MRussell\REST\Exception\Endpoint\InvalidRequest
     */
    public function execute(){
        if (isset($this->Endpoint)){
            $this->Endpoint->getData()->update($this->asArray());
            return $this->Endpoint->execute();
        }
        return false;
    }

}