<?php
/**
 * User: mrussell
 * Date: 4/28/17
 * Time: 1:33 PM
 */

namespace Sugarcrm\REST\Tests\Stubs\Auth;


use Sugarcrm\REST\Auth\SugarOAuthController;

class SugarOAuthStub extends SugarOAuthController
{
    protected $token = array(
        'access_token' => 'bar',
        'refresh_token' => 'foo',
        'expires_in' => '3600'
    );

    public function authenticate(): bool
    {
        return TRUE;
    }

    public function refresh(): bool
    {
        return TRUE;
    }

    public function logout(): bool
    {
        return TRUE;
    }
}