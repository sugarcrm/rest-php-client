<?php
/**
 * ©[2022] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace Sugarcrm\REST\Tests\Stubs\Auth;


use Sugarcrm\REST\Auth\SugarOAuthController;

class SugarOAuthStub extends SugarOAuthController {
    protected $token = array(
        'access_token' => 'bar',
        'refresh_token' => 'foo',
        'expires_in' => '3600'
    );

    public function authenticate(): bool
    {
        return true;
    }

    public function refresh(): bool
    {
        return true;
    }

    public function logout(): bool
    {
        $this->token = null;
        return true;
    }
} 