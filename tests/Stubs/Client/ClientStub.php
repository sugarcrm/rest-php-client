<?php
/**
 * ©[2016] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace SugarAPI\SDK\Tests\Stubs\Client;

use SugarAPI\SDK\Client\Abstracts\AbstractClient;

class ClientStub extends AbstractClient {

    /**
     * @inheritdoc
     */
    public function login() {
        $this->setToken(array('token' => '1234'));
        return TRUE;
    }

    /**
     * @inheritdoc
     */
    public function refreshToken(){
        $this->setToken(array('token' => '5678'));
        return TRUE;
    }

}