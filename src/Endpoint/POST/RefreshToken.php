<?php
/**
 * ©[2016] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace SugarAPI\SDK\Endpoint\POST;

class RefreshToken extends Oauth2Token
{
    /**
     * @inheritdoc
     */
    protected $_REQUIRED_DATA = array(
        'grant_type' => 'refresh_token',
        'refresh_token' => null,
        'client_id' => null,
        'client_secret' => null
    );
}
