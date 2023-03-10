<?php
/**
 * ©[2022] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace Sugarcrm\REST\Client;

/**
 * Default implementation for PlatformAwareInterface
 * @package Sugarcrm\Rest\Client
 * @implements PlatformAwareInterface
 */
trait PlatformAwareTrait
{
    /**
     * The Sugar API Platform
     * - Defaults to 'base'
     * @var string
     */
    protected $platform = 'base';

    /**
     * Set the platform
     * @implements PlatformAwareInterface
     * @param string $platform
     * @return $this
     */
    public function setPlatform(string $platform)
    {
        $this->platform = $platform;
        return $this;
    }

    /**
     * Get the platform
     * @implements PlatformAwareInterface
     * @return string
     */
    public function getPlatform(): string
    {
        return $this->platform;
    }
}
