<?php
/**
 * ©[2022] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace Sugarcrm\REST\Client;

/**
 * Interface for an object to be Sugar platform aware
 */
interface PlatformAwareInterface
{
    /**
     * Set the API platform
     * @param string $platform
     * @return mixed
     */
    public function setPlatform(string $platform);

    /**
     * Get the API Platform
     * @return string
     */
    public function getPlatform(): string;
}