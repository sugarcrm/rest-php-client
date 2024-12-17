<?php

/**
 * ©[2024] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace Sugarcrm\REST\Client;

/**
 * Interface for an object to be Sugar platform aware
 * @package Sugarcrm\Rest\Client
 */
interface PlatformAwareInterface
{
    /**
     * Set the API platform
     * @return mixed
     */
    public function setPlatform(string $platform);

    /**
     * Get the API Platform
     */
    public function getPlatform(): string;
}
