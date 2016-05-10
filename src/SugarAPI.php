<?php
/**
 * ©[2016] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace SugarAPI\SDK;

use SugarAPI\SDK\Client\Abstracts\AbstractClient;
use SugarAPI\SDK\Client\Abstracts\AbstractSugarClient;
use SugarAPI\SDK\Endpoint\Interfaces\EPInterface;

/**
 * The default SDK Client Implemntation
 * @package SugarAPI\SDK
 * @inheritdoc
 */
class SugarAPI extends AbstractSugarClient {

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

    /**
     * @inheritdoc
     * Overrides only the credentials properties passed in, instead of entire credentials array
     */
    public function setCredentials(array $credentials){
        foreach ($this->credentials as $key => $value){
            if (isset($credentials[$key])){
                $this->credentials[$key] = $credentials[$key];
            }
        }
        return parent::setCredentials($this->credentials);
    }

}