<?php
/**
 * ©[2016] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace SugarAPI\SDK\Response\Abstracts;

use SugarAPI\SDK\Response\Interfaces\ResponseInterface;

abstract class AbstractResponse implements ResponseInterface
{
    /**
     * The Curl Request Resource that was used when curl_exec was called
     * @var cURL resource handle
     */
    protected $CurlRequest;

    /**
     * Extracted headers from Curl Response
     * @var string
     */
    protected $headers;

    /**
     * Extracted body from Curl Response
     * @var mixed
     */
    protected $body;

    /**
     * The HTTP Status Code of Request
     * @var string
     */
    protected $status;

    /**
     * The last Curl Error that occurred
     * @var string|boolean - False when no Curl Error = 0
     */
    protected $error;

    /**
     * The cURL Resource information returned via curl_getinfo
     * @var array
     */
    protected $info;

    public function __construct($curlRequest, $curlResponse = null)
    {
        $this->CurlRequest = $curlRequest;
        if ($curlResponse !== null) {
            $this->setCurlResponse($curlResponse);
        }
    }

    public function setCurlResponse($curlResponse)
    {
        $this->extractInfo();
        if ($this->error === false) {
            $this->extractResponse($curlResponse);
        }
    }

    /**
     * Extract the information from the Curl Request via curl_getinfo
     * Setup the Status property to be equal to the http_code
     */
    protected function extractInfo()
    {
        $this->info = curl_getinfo($this->CurlRequest);
        $this->status = $this->info['http_code'];
        if (curl_errno($this->CurlRequest)!== CURLE_OK) {
            $this->error = curl_error($this->CurlRequest);
        } else {
            $this->error = false;
        }
    }

    /**
     * Seperate the Headers and Body from the CurlResponse, and set the object properties
     * @param string $curlResponse
     */
    protected function extractResponse($curlResponse)
    {
        $this->headers = substr($curlResponse, 0, $this->info['header_size']);
        $this->body = substr($curlResponse, $this->info['header_size']);
    }

    /**
     * @inheritdoc
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @inheritdoc
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @inheritdoc
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * @inheritdoc
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * @inheritdoc
     */
    public function getInfo()
    {
        return $this->info;
    }
}
