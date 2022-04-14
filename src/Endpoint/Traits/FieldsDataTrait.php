<?php

namespace Sugarcrm\REST\Endpoint\Traits;

use MRussell\REST\Endpoint\Data\DataInterface;
use Sugarcrm\REST\Endpoint\Abstracts\AbstractSugarBeanCollectionEndpoint;

/**
 * Default setup for managing fields and view data on an Endpoint
 * @package Sugarcrm\REST\Endpoint\Traits
 */
trait FieldsDataTrait
{
    /**
     * @var array
     */
    protected $_fields = [];

    /**
     * @var string
     */
    protected $_view = '';

    /**
     * Get the fields that are being requested via API
     * @return array
     */
    public function getFields(): array
    {
        return $this->_fields;
    }

    /**
     * Set the fields array property
     * @param array $_fields
     * @return $this
     */
    public function setFields(array $_fields)
    {
        $this->_fields = $_fields;
        return $this;
    }

    /**
     * Add a fields to the fields array
     * @param $field
     * @return $this
     */
    public function addField($field)
    {
        if (!in_array($field,$this->_fields)){
            $this->_fields[] = $field;
        }
        return $this;
    }

    /**
     * Set the view to send via data
     * @param string $view
     * @return $this
     */
    public function setView(string $view)
    {
        $this->_view = $view;
        return $this;
    }

    /**
     * Get the view configured
     * @return string
     */
    public function getView(): string {
        return $this->_view;
    }

    /**
     * @param array|\ArrayAccess|DataInterface $data
     * @return void
     */
    protected function configureFieldsDataProps($data){
        $fields = $this->getFields();
        if (!empty($fields)){
            $data[AbstractSugarBeanCollectionEndpoint::SUGAR_FIELDS_DATA_PROPERTY] = implode(',',$this->getFields());
        }
        if (!empty($this->getView())){
            $data[AbstractSugarBeanCollectionEndpoint::SUGAR_VIEW_DATA_PROPERTY] = $this->getView();
        }
        return $data;
    }
}