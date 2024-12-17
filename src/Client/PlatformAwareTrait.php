<?php

/**
 * ©[2024] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
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
     */
    public function getPlatform(): string
    {
        return $this->platform;
    }
}
