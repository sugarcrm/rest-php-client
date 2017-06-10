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

    protected $orderBy = '';

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
     * Add orderBy based on Endpoint Property
     * @inheritdoc
     */
    protected function configureData($data)
    {
        $data[self::SUGAR_ORDERBY_PROPERTY] = $this->orderBy;
        return parent::configureData($data);
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
                $this->collection = $responseBody['records'];
            }
        }
    }
}