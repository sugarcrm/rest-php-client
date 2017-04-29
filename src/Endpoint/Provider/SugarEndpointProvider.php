<?php
/**
 * ©[2016] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace Sugarcrm\REST\Endpoint\Provider;

use MRussell\REST\Endpoint\Provider\AbstractEndpointProvider;

class SugarEndpointProvider extends AbstractEndpointProvider
{
    protected static $_DEFAULT_ENDPOINTS = array(
        'module' => array(
            'class' => 'Sugarcrm\\REST\\Endpoint\\Module',
            'properties' => array()
        ),
        'list' => array(
            'class' => 'Sugarcrm\\REST\\Endpoint\\ModuleFilter',
            'properties' => array()
        ),
        'search' => array(
            'class' => 'Sugarcrm\\REST\\Endpoint\\Search',
            'properties' => array()
        ),
        'metadata' => array(
            'class' => 'Sugarcrm\\REST\\Endpoint\\Metadata',
            'properties' => array()
        ),
        'oauth2Token' => array(
            'class' => 'Sugarcrm\\REST\\Endpoint\\OAuth2Token',
            'properties' => array()
        ),
        'oauth2Refresh' => array(
            'class' => 'Sugarcrm\\REST\\Endpoint\\OAuth2Refresh',
            'properties' => array()
        ),
        'oauth2Logout' => array(
            'class' => 'Sugarcrm\\REST\\Endpoint\\OAuth2Logout',
            'properties' => array()
        ),
        'me' => array(
            'class' => 'Sugarcrm\\REST\\Endpoint\\Me',
            'properties' => array()
        ),
        'bulk' => array(
            'class' => 'Sugarcrm\\REST\\Endpoint\\Bulk',
            'properties' => array()
        )
    );

}