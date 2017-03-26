<?php

namespace Sugarcrm\REST\Endpoint\Data\Filters;

interface FilterInterface
{
    /**
     * Compiles the Filter Object an array to be passed to Sugar Filter API
     * @return array
     */
    public function compile();
}