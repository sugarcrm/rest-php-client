<?php
/**
 * ©[2017] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace Sugarcrm\REST\Endpoint;

use MRussell\Http\Request\JSON;
use Sugarcrm\REST\Endpoint\Abstracts\AbstractSmartSugarEndpoint;

/**
 * Class Bulk
 * @package Sugarcrm\REST\Endpoint
 */
class Bulk extends AbstractSmartSugarEndpoint
{
    /**
     * @inheritdoc
     */
    protected static $_ENDPOINT_URL = 'bulk';

    /**
     * @inheritdoc
     */
    protected static $_DATA_CLASS = 'Sugarcrm\REST\Endpoint\Data\BulkRequest';

    /**
     * @var array
     */
    protected static $_DEFAULT_PROPERTIES = array(
        'auth' => TRUE,
        'httpMethod' => JSON::HTTP_POST,
        'data' => array(
            'required' => array(
                'requests' => 'array'
            ),
            'defaults' => array()
        )
    );

}