<?php
/**
 * ©[2016] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace SugarAPI\SDK\Tests\Stubs\Request;

use SugarAPI\SDK\Request\Abstracts\AbstractRequest;

class RequestStub extends AbstractRequest {

    protected static $_DEFAULT_OPTIONS = array(
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_0,
        CURLOPT_HEADER => TRUE,
        CURLOPT_SSL_VERIFYPEER => FALSE,
        CURLOPT_FOLLOWLOCATION => FALSE,
        CURLOPT_USERAGENT => 'SugarAPI-SDK-PHP'
    );
}