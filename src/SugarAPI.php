<?php
/**
 * ©[2016] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace SugarAPI\SDK;

use SugarAPI\SDK\Client\Abstracts\AbstractClient;
use SugarAPI\SDK\EntryPoint\Interfaces\EPInterface;

/**
 * The default SDK Client Implemntation
 * @package SugarAPI\SDK
 * @method EPInterface ping()
 * @method EPInterface getRecord(string $module = '')
 * @method EPInterface getAttachment(string $module = '',string $record_id = '')
 * @method EPInterface getChangeLog(string $module = '',string $record_id = '')
 * @method EPInterface filterRelated(string $module = '')
 * @method EPInterface getRelated(string $module = '',string $record_id = '',string $relationship = '',string $related_id = '')
 * @method EPInterface me()
 * @method EPInterface search()
 * @method EPInterface oauth2Token()
 * @method EPInterface oauth2Refresh()
 * @method EPInterface createRecord()
 * @method EPInterface filterRecords()
 * @method EPInterface attachFile()
 * @method EPInterface oauth2Logout()
 * @method EPInterface createRelated()
 * @method EPInterface linkRecords()
 * @method EPInterface bulk()
 * @method EPInterface updateRecord()
 * @method EPInterface favorite()
 * @method EPInterface deleteRecord()
 * @method EPInterface unfavorite()
 * @method EPInterface deleteFile()
 * @method EPInterface unlinkRecords()
 */
class SugarAPI extends AbstractClient {

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