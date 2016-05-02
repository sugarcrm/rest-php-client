<?php
/**
 * ©[2016] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace SugarAPI\SDK\Helpers;

class Helpers {

    const API_URL = '/rest/v10/';

    const SDK_VERSION = '1.0';

    /**
     * Given a sugarcrm server/instance generate the Rest/v10 API Url
     * @param $instance
     * @return string
     */
    public static function configureAPIURL($instance){
        if (strpos($instance,"http")===FALSE){
            $instance = "http://".$instance;
        }
        if (strpos($instance,"rest/v10")!==FALSE){
            $instance = str_replace("rest/v10", "", $instance);
        }
        return rtrim($instance, "/").self::API_URL;
    }

    /**
     * Return the current SDK Version
     * @return string
     */
    public static function getSDKVersion(){
        return self::SDK_VERSION;
    }

    /**
     * Return the list of EntryPoints that come with the SDK
     * @return array
     */
    public static function getSDKEntryPointRegistry(){
        $entryPoints = array();
        require __DIR__.DIRECTORY_SEPARATOR.'registry.php';
        foreach ($entryPoints as $funcName => $className) {
            $className = "SugarAPI\\SDK\\EntryPoint\\" . $className;
            $entryPoints[$funcName] = $className;
        }
        return $entryPoints;
    }

}