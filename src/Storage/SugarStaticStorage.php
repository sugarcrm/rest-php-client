<?php

namespace Sugarcrm\REST\Storage;

use MRussell\REST\Storage\StaticStorage;

class SugarStaticStorage extends StaticStorage
{
    protected $namespace = 'sugarapi';

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

    public function store($key, $value)
    {
        $key = $this->formatKey($key);
        if (is_array($value) || is_object($value)){
            $value = json_encode($value);
        }
        return parent::store($key,$value);
    }

    public function remove($key)
    {
        $key = $this->formatKey($key);
        return parent::remove($key);
    }

    public function get($key)
    {
        $key = $this->formatKey($key);
        return parent::get($key);
    }
}