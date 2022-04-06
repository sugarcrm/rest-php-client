<?php
/**
 * ©[2022] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace Sugarcrm\REST\Endpoint\Abstracts;

use MRussell\REST\Endpoint\Abstracts\AbstractModelEndpoint;
use MRussell\REST\Endpoint\Interfaces\EndpointInterface;
use Sugarcrm\REST\Endpoint\Traits\FieldsDataTrait;
use Sugarcrm\REST\Endpoint\Traits\ModuleAwareTrait;

/**
 * Abstract implementation of SugarBean Collections for Sugar 7 REST Api
 * - Works with a single module
 * - Built in fields tracking
 * - Built in order by tracking
 * @package Sugarcrm\REST\Endpoint\Abstracts
 */
abstract class AbstractSugarBeanCollectionEndpoint extends AbstractSugarCollectionEndpoint
{
    use FieldsDataTrait, ModuleAwareTrait;

    const SUGAR_ORDERBY_DATA_PROPERTY = 'order_by';

    const SUGAR_FIELDS_DATA_PROPERTY = 'fields';

    const SUGAR_VIEW_DATA_PROPERTY = 'view';

    protected static $_MODEL_CLASS = 'Sugarcrm\\REST\\Endpoint\\Module';

    protected static $_RESPONSE_PROP = 'records';

    /**
     * Order By statement
     * @var string
     */
    protected $orderBy = '';

    public function setUrlArgs(array $args): EndpointInterface
    {
        $this->configureModuleArg($args);
        return parent::setUrlArgs($args);
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
     * Add fields based on Endpoint property
     * @inheritdoc
     */
    protected function configurePayload()
    {
        $data = parent::configurePayload();
        if ($this->getOrderBy() !== ''){
            $data[self::SUGAR_ORDERBY_DATA_PROPERTY] = $this->getOrderBy();
        }
        $data = $this->configureFieldsDataProps($data);
        return $data;
    }

    /**
     * Add module to url options
     * @inheritdoc
     */
    protected function configureURL(array $urlArgs): string
    {
        $urlArgs['module'] = $this->getModule();
        return parent::configureURL($urlArgs);
    }

    /**
     * @inheritdoc
     */
    protected function buildModel(array $data = array()): AbstractModelEndpoint
    {
        $Model = parent::buildModel($data);
        if ($Model instanceof AbstractSugarBeanEndpoint){
            $module = $this->getModule();
            if (!empty($module) && $module !== '') {
                $Model->setModule($this->getModule());
            } else if (isset($Model['_module'])) {
                $Model->setModule($Model['_module']);
            }
        }
        return $Model;
    }
}