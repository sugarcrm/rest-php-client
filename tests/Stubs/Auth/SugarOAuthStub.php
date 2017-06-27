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

    public function authenticate()
    {
        return TRUE;
    }

    public function refresh()
    {
        return TRUE;
    }

    public function logout()
    {
        return TRUE;
    }
}