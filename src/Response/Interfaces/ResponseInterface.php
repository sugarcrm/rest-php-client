<?php
/**
 * ©[2016] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace SugarAPI\SDK\Response\Interfaces;

interface ResponseInterface
{
    /**
     * Provide the Raw Curl Response resource from curl_exec
     * @param mixed $curlResponse
     * @return self
     */
    public function setCurlResponse($curlResponse);

    /**
     * Get the Response HTTP Status Code
     * @return string
     */
    public function getStatus();

    /**
     * Get the Response Body
     * @return string
     */
    public function getBody();

    /**
     * Get the Response Headers
     * @return string
     */
    public function getHeaders();

    /**
     * Get the Information about the Curl Request
     * @return array
     */
    public function getInfo();

    /**
     * Get the Request Errors if they occurred
     * @return string|boolean
     */
    public function getError();
}
