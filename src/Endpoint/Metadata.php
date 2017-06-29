<?php
/**
 * ©[2017] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace Sugarcrm\REST\Endpoint;

use MRussell\Http\Request\Curl;
use Sugarcrm\REST\Endpoint\Abstracts\AbstractSugarEndpoint;

/**
 * Class Metadata
 * @package Sugarcrm\REST\Endpoint
 */
class Metadata extends AbstractSugarEndpoint
{
    const METADATA_TYPE_HASH = '_hash';

    const METADATA_TYPE_PUBLIC = 'public';

    /**
     * @inheritdoc
     */
    protected static $_ENDPOINT_URL = 'metadata/$:type';

    /**
     * @inheritdoc
     */
    protected static $_DEFAULT_PROPERTIES = array(
        'auth' => TRUE,
        'httpMethod' => Curl::HTTP_GET
    );

    /**
     * Gets the Metadata Hash
     * @return $this
     * @throws \MRussell\REST\Exception\Endpoint\InvalidRequest
     */
    public function getHash(){
        $this->setOptions(array(self::METADATA_TYPE_HASH));
        return $this->execute();
    }

    /**
     * Gets the Public Metadata
     * @return $this
     * @throws \MRussell\REST\Exception\Endpoint\InvalidRequest
     */
    public function getPublic(){
        $this->setOptions(array(self::METADATA_TYPE_PUBLIC));
        if (!$this->getAuth()->isAuthenticated()){
            $this->setProperty('auth',FALSE);
        }
        $this->execute();
        $this->setProperty('auth',TRUE);
        return $this;
    }
}