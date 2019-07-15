<?php
/**
 * ©[2019] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace Sugarcrm\REST\Helpers;

class Helper
{
    const API_VERSION = "10";

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

}
