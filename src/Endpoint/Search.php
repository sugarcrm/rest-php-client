<?php
/**
 * ©[2016] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace Sugarcrm\REST\Endpoint;


use Sugarcrm\REST\Endpoint\Abstracts\AbstractSugarBeanCollectionEndpoint;

/**
 * Class Search
 * @package Sugarcrm\REST\Endpoint
 */
class Search extends AbstractSugarBeanCollectionEndpoint
{
    /**
     * @inheritdoc
     */
    protected static $_MODEL_CLASS = 'Sugarcrm\\REST\\Endpoint\\Module';

    /**
     * @inheritdoc
     */
    protected static $_ENDPOINT_URL = 'search';

    /**
     * When retrieveing the Model from the collection, we can use the _module property to set the Module
     * @inheritdoc
     */
    public function get($id)
    {
        $model = parent::get($id);
        if (is_object($model)){
            $model->setModule($model['_module']);
        }
        return $model;
    }
}