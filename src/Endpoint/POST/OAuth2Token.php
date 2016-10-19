<?php
/**
 * ©[2016] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace SugarAPI\SDK\Endpoint\POST;

use SugarAPI\SDK\Endpoint\Abstracts\POST\AbstractPostEndpoint;

class OAuth2Token extends AbstractPostEndpoint
{
    protected $_AUTH_REQUIRED = false;
    protected $_URL = 'oauth2/token';
    protected $_REQUIRED_DATA = array(
        'username' => null,
        'password' => null,
        'grant_type' => 'password',
        'client_id' => null,
        'client_secret' => null,
        'platform' => null
    );
    protected $_DATA_TYPE = 'array';
}
