<?php
/**
 * ©[2022] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace Sugarcrm\REST\Endpoint\Provider;

use MRussell\REST\Endpoint\Provider\DefaultEndpointProvider;

/**
 * @package Sugarcrm\REST\Endpoint\Provider
 */
class SugarEndpointProvider extends DefaultEndpointProvider
{
    protected $registry = array(
        'module' => array(
            'class' => \Sugarcrm\REST\Endpoint\Module::class,
            'properties' => array()
        ),
        'list' => array(
            'class' => \Sugarcrm\REST\Endpoint\ModuleFilter::class,
            'properties' => array()
        ),
        'search' => array(
            'class' => \Sugarcrm\REST\Endpoint\Search::class,
            'properties' => array()
        ),
        'metadata' => array(
            'class' => \Sugarcrm\REST\Endpoint\Metadata::class,
            'properties' => array()
        ),
        'oauth2Token' => array(
            'class' => \Sugarcrm\REST\Endpoint\OAuth2Token::class,
            'properties' => array()
        ),
        'oauth2Refresh' => array(
            'class' => \Sugarcrm\REST\Endpoint\OAuth2Refresh::class,
            'properties' => array()
        ),
        'oauth2Logout' => array(
            'class' => \Sugarcrm\REST\Endpoint\OAuth2Logout::class,
            'properties' => array()
        ),
        'oauth2Sudo' => array(
            'class' => \Sugarcrm\REST\Endpoint\OAuth2Sudo::class,
            'properties' => array()
        ),
        'me' => array(
            'class' => \Sugarcrm\REST\Endpoint\Me::class,
            'properties' => array()
        ),
        'bulk' => array(
            'class' => \Sugarcrm\REST\Endpoint\Bulk::class,
            'properties' => array()
        ),
        'enum' => array(
            'class' => \Sugarcrm\REST\Endpoint\Enum::class,
            'properties' => array()
        ),
        'ping' => array(
            'class' => \Sugarcrm\REST\Endpoint\Ping::class,
            'properties' => array()
        ),
        'Note' => array(
            'class' => \Sugarcrm\REST\Endpoint\Note::class,
            'properties' => array()
        )
    );

}