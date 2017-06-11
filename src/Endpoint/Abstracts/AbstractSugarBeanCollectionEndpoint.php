<?php
/**
 * ©[2017] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace Sugarcrm\REST\Endpoint\Abstracts;

/**
 * Class AbstractSugarBeanCollectionEndpoint
 * @package Sugarcrm\REST\Endpoint\Abstracts
 */
abstract class AbstractSugarBeanCollectionEndpoint extends AbstractSugarCollectionEndpoint
{
    const SUGAR_ORDERBY_PROPERTY = 'order_by';

    const SUGAR_FIELDS_PROPERTY = 'fields';

    protected static $_MODEL_CLASS = 'Sugarcrm\\REST\\Endpoint\\Module';

    /**
     * Order By statement
     * @var string
     */
    protected $orderBy = '';

    /**
     * Fields requested
     * @var array
     */
    protected $fields = array();

    /**
     * The SugarCRM Module being used
     * @var string
     */
    protected $module;

    public function setOptions(array $options)
    {
        $opts = array();
        if (isset($options[0])) {
            $this->setModule($options[0]);
            $opts['module'] = $this->module;
        }
        return parent::setOptions($opts);
    }

    /**
     * Set the Sugar Module currently being used
     * @param $module
     * @return $this
     */
    public function setModule($module)
    {
        $this->module = $module;
        return $this;
    }

    /**
     * Get the Sugar Module currently configured
     * @return mixed
     */
    public function getModule()
    {
        return $this->module;
    }

    /**
     * Get the orderBy Property on the Endpoint
     * @return string
     */
    public function getOrderBy()
    {
        return $this->orderBy;
    }

    /**
     * Set the orderBy Property on the Endpoint
     * @param $orderBy
     * @return $this
     */
    public function setOrderBy($orderBy)
    {
        $this->orderBy = $orderBy;
        return $this;
    }

    /**
     * Get the fields that are being requested via API
     * @return array
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * Set the fields array property
     * @param array $fields
     * @return $this
     */
    public function setFields(array $fields)
    {
        $this->fields = $fields;
        return $this;
    }

    /**
     * Add a fields to the fields array
     * @param $field
     * @return $this
     */
    public function addField($field)
    {
        if (!in_array($field,$this->fields)){
            $this->fields[] = $field;
        }
        return $this;
    }

    /**
     * Add orderBy based on Endpoint Property
     * Add fields based on Endpoint property
     * @inheritdoc
     */
    protected function configureData($data)
    {
        if ($this->getOrderBy() !== ''){
            $data[self::SUGAR_ORDERBY_PROPERTY] = $this->getOrderBy();
        }
        $fields = $this->getFields();
        if (!empty($fields)){
            $data[self::SUGAR_FIELDS_PROPERTY] = implode(',',$this->getFields());
        }
        return parent::configureData($data);
    }

    /**
     * Add module to url options
     * @inheritdoc
     */
    protected function configureURL(array $options)
    {
        $options['module'] = $this->module;
        return parent::configureURL($options);
    }

    /**
     * @inheritdoc
     */
    protected function updateCollection()
    {
        $responseBody = $this->Response->getBody();
        if (!empty($responseBody['records'])) {
            if (isset($this->model)) {
                $modelIdKey = $this->buildModel()->modelIdKey();
                foreach ($responseBody['records'] as $key => $model) {
                    if (isset($model[$modelIdKey])) {
                        $this->collection[$model[$modelIdKey]] = $model;
                    } else {
                        $this->collection[] = $model;
                    }
                }
            } else {
                // @codeCoverageIgnoreStart
                $this->collection = $responseBody['records'];
                // @codeCoverageIgnoreEnd
            }
        }
    }

    /**
     * @inheritdoc
     */
    protected function buildModel(array $data = array())
    {
        $Model = parent::buildModel($data);
        $module = $this->getModule();
        if (!empty($module) && $module !== '') {
            $Model->setModule($this->module);
        } else if (isset($Model['_module'])) {
            $Model->setModule($Model['_module']);
        }
        return $Model;
    }
}