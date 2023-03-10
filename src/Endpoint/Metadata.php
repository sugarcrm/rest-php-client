<?php
/**
 * ©[2022] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace Sugarcrm\REST\Endpoint;

use Sugarcrm\REST\Endpoint\Abstracts\AbstractSugarEndpoint;

/**
 * Metadata Endpoint provides access to the defined Metadata of the system
 * @package Sugarcrm\REST\Endpoint
 */
class Metadata extends AbstractSugarEndpoint
{
    public const METADATA_TYPE_HASH = '_hash';

    public const METADATA_TYPE_PUBLIC = 'public';

    /**
     * @inheritdoc
     */
    protected static $_ENDPOINT_URL = 'metadata/$:type';

    /**
     * @inheritdoc
     */
    protected static $_DEFAULT_PROPERTIES = array(
        'auth' => true,
        'httpMethod' => "GET"
    );

    /**
     * Gets the Metadata Hash
     * @return $this
     * @throws \MRussell\REST\Exception\Endpoint\InvalidRequest
     */
    public function getHash()
    {
        $this->setUrlArgs(array(self::METADATA_TYPE_HASH));
        return $this->execute();
    }

    /**
     * Gets the Public Metadata
     * @return $this
     * @throws \MRussell\REST\Exception\Endpoint\InvalidRequest
     */
    public function getPublic()
    {
        $this->setUrlArgs(array(self::METADATA_TYPE_PUBLIC));
        $this->setProperty('auth', true);
        $this->execute();
        $this->setProperty('auth', true);
        return $this;
    }
}
