<?php

namespace Sugarcrm\REST\Client;

/**
 * Interface for an object to be Sugar platform aware
 */
interface PlatformAwareInterface
{
    public function setPlatform(string $platform): self;

    public function getPlatform(): string;
}