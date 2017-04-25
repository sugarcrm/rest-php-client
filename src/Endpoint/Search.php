<?php

namespace Sugarcrm\REST\Endpoint;


use Sugarcrm\REST\Endpoint\Abstracts\AbstractSugarBeanCollectionEndpoint;

class Search extends AbstractSugarBeanCollectionEndpoint
{
    protected static $_MODEL_CLASS = 'Sugarcrm\\REST\\Endpoint\\Module';

    protected static $_ENDPOINT_URL = 'search';

    public function get($id)
    {
        $model = parent::get($id);
        $model->setModule($model['_module']);
        return $model;
    }
}