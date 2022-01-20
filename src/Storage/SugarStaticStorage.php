<?php

namespace Sugarcrm\REST\Storage;

use MRussell\REST\Storage\StaticStorage;

/**
 * Static Storage implementation allows for API Tokens to be reuseable through a single PHP Process
 * - Consider extended this when implementing a solution to store tokens across processes, so that less calls to
 *      non-volatile storage need to occur
 * @package Sugarcrm\REST\Storage
 */
class SugarStaticStorage extends StaticStorage
{
    protected $namespace = 'sugarapi';

    /**
     * Given an array key (based on Auth Credentials), generate a string key
     * @param $key
     * @return string
     */
    protected function formatKey($key){
        $return = '';
        if (is_array($key)){
            if (isset($key['server'])){
                $return .= $key['server']."_";
            }
            if (isset($key['client_id'])){
                $return .= $key['client_id']."_";
            }
            if (isset($key['platform'])){
                $return .= $key['platform']."_";
            }
            if (isset($key['sudo'])){
                $return .= "sudo".$key['sudo'];
            }
            $return = rtrim($return,"_");
        }else {
            $return = $key;
        }
        return $return;
    }

    /**
     * Store a value by key
     * @param $key
     * @param $value
     * @return bool
     */
    public function store($key, $value): bool
    {
        $key = $this->formatKey($key);
        return parent::store($key,$value);
    }

    /**
     * Remove a value by key
     * @param $key
     * @return bool
     */
    public function remove($key): bool
    {
        $key = $this->formatKey($key);
        return parent::remove($key);
    }

    /**
     * Retrieve a value by key
     * @param $key
     * @return mixed
     */
    public function get($key)
    {
        $key = $this->formatKey($key);
        return parent::get($key);
    }
}