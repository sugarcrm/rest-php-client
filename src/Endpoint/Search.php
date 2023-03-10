<?php
/**
 * ©[2022] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace Sugarcrm\REST\Endpoint;

use Sugarcrm\REST\Endpoint\Abstracts\AbstractSugarCollectionEndpoint;

/**
 * The Sugar 7 REST Api Search Endpoint
 * - Provides access to global Elastic Search queries
 * @package Sugarcrm\REST\Endpoint
 */
class Search extends AbstractSugarCollectionEndpoint
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
     * @return Module
     */
    public function get($id)
    {
        $Model = parent::get($id);
        if (is_object($Model)) {
            if (isset($Model['_module'])) {
                $Model->setModule($Model['_module']);
            }
        }
        return $Model;
    }
}
