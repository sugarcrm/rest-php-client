<?php
/**
 * ©[2016] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace SugarAPI\SDK;

use SugarAPI\SDK\Client\Abstracts\AbstractClient;
use SugarAPI\SDK\Client\Abstracts\AbstractSugarClient;
use SugarAPI\SDK\Endpoint\Interfaces\EPInterface;

/**
 * The default SDK Client Implementation
 * @package SugarAPI\SDK
 * @inheritdoc
 */
class SugarAPI extends AbstractSugarClient
{
    /**
     * The configured Authentication options
     * @var array
     */
    protected $credentials = array(
        'username' => '',
        'password' => '',
        'client_id' => 'sugar',
        'client_secret' => '',
        'platform' => 'api'
    );
}
