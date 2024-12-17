<?php

/**
 * ©[2024] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace Sugarcrm\REST\Endpoint\Provider;

use Sugarcrm\REST\Endpoint\Module;
use Sugarcrm\REST\Endpoint\ModuleFilter;
use Sugarcrm\REST\Endpoint\ModuleAudit;
use Sugarcrm\REST\Endpoint\Search;
use Sugarcrm\REST\Endpoint\Metadata;
use Sugarcrm\REST\Endpoint\OAuth2Token;
use Sugarcrm\REST\Endpoint\OAuth2Refresh;
use Sugarcrm\REST\Endpoint\OAuth2Logout;
use Sugarcrm\REST\Endpoint\OAuth2Sudo;
use Sugarcrm\REST\Endpoint\Me;
use Sugarcrm\REST\Endpoint\Bulk;
use Sugarcrm\REST\Endpoint\Enum;
use Sugarcrm\REST\Endpoint\Ping;
use Sugarcrm\REST\Endpoint\Note;
use MRussell\REST\Endpoint\Provider\DefaultEndpointProvider;

/**
 * @package Sugarcrm\REST\Endpoint\Provider
 */
class SugarEndpointProvider extends DefaultEndpointProvider
{
    protected $registry = [
        'module' => [
            'class' => Module::class,
            'properties' => [],
        ],
        'list' => [
            'class' => ModuleFilter::class,
            'properties' => [],
        ],
        'audit' => [
            'class' => ModuleAudit::class,
            'properties' => [],
        ],
        'search' => [
            'class' => Search::class,
            'properties' => [],
        ],
        'metadata' => [
            'class' => Metadata::class,
            'properties' => [],
        ],
        'oauth2Token' => [
            'class' => OAuth2Token::class,
            'properties' => [],
        ],
        'oauth2Refresh' => [
            'class' => OAuth2Refresh::class,
            'properties' => [],
        ],
        'oauth2Logout' => [
            'class' => OAuth2Logout::class,
            'properties' => [],
        ],
        'oauth2Sudo' => [
            'class' => OAuth2Sudo::class,
            'properties' => [],
        ],
        'me' => [
            'class' => Me::class,
            'properties' => [],
        ],
        'bulk' => [
            'class' => Bulk::class,
            'properties' => [],
        ],
        'enum' => [
            'class' => Enum::class,
            'properties' => [],
        ],
        'ping' => [
            'class' => Ping::class,
            'properties' => [],
        ],
        'Note' => [
            'class' => Note::class,
            'properties' => [],
        ],
    ];
}
