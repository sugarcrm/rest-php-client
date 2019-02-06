<?php
/**
 * ©[2016] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace SugarAPI\SDK\Helpers;

class Helpers
{
    const API_VERSION = 10;
    const API_URL = '/rest/v%s/';

    /**
     * Given a sugarcrm server/instance generate the Rest/v10 API Url
     * @param $instance
     * @param int $version
     * @return string
     */
    public static function configureAPIURL($instance, $version = null)
    {
        $url = 0;
        $version = ($version === null ? self::API_VERSION : $version);
        $instance = preg_replace('/\/rest\/v.+/', '', $instance);
        $url = rtrim($instance,"/").sprintf(self::API_URL, $version);
        if (preg_match('/^(http|https):\/\//i', $url) === 0) {
            $url = "http://".$url;
        }
        return $url;
    }

    /**
     * Return the list of Endpoints that come with the SDK
     * @return array
     */
    public static function getSDKEndpointRegistry()
    {
        $entryPoints = array();
        require __DIR__.DIRECTORY_SEPARATOR.'registry.php';
        foreach ($entryPoints as $funcName => $className) {
            $className = "SugarAPI\\SDK\\Endpoint\\" . $className;
            $entryPoints[$funcName] = $className;
        }
        return $entryPoints;
    }
}
