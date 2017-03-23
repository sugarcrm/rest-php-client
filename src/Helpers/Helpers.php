<?php
/**
 * ©[2016] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace Sugarcrm\REST\Helpers;

class Helpers
{
    const API_VERSION = 10;
    const API_URL = '/rest/v%d/';

    /**
     * Given a sugarcrm server/instance generate the Rest/v10 API Url
     * @param $instance
     * @param int $version
     * @return string
     */
    public static function configureAPIURL($instance, $version = null)
    {
        $url = 0;
        $instance = strtolower(rtrim($instance, "/"));
        $version = ($version === null ? self::API_VERSION : intval($version));
        if (preg_match('/^(http|https):\/\//i', $instance) === 0) {
            $instance = "http://".$instance;
        }
        $instance = preg_replace('/\/rest\/v\d+/', '', $instance);
        $url = $instance.sprintf(self::API_URL, $version);
        return $url;
    }

}
