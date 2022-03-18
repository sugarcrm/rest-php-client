<?php

namespace Sugarcrm\REST\Client;

trait PlatformAwareTrait
{
    protected $platform = 'base';

    /**
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
     * @implements PlatformAwareInterface
     * @return string
     */
    public function getPlatform(): string
    {
        return $this->platform;
    }
}