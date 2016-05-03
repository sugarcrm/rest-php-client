<?php
/**
 * ©[2016] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace SugarAPI\SDK\Endpoint\Interfaces;

use SugarAPI\SDK\Request\Interfaces\RequestInterface;
use SugarAPI\SDK\Response\Interfaces\ResponseInterface;

interface EPInterface {

    /**
     * Set the URL options on the Endpoint, such as Record ID
     * @param array
     * @return self
     */
    public function setOptions(array $options);

    /**
     * Actually sets the data on the Endpoint, and on the Request object. Raw data is passed here
     * @param $data
     */
    public function setData($data);

    /**
     * Set the full URL that the Endpoint submits data to
     * @param $url
     */
    public function setUrl($url);

    /**
     * Set the Request Object used by the Endpoint
     * @param RequestInterface $Request
     * @return self
     */
    public function setRequest(RequestInterface $Request);

    /**
     * Set the Response Object used by the Endpoint
     * @param ResponseInterface $Response
     * @return self
     */
    public function setResponse(ResponseInterface $Response);

    /**
     * Configure OAuth Token on Header
     * @param string
     * @return self
     */
    public function setAuth($accessToken);

    /**
     * Check if Authentication is needed
     * @return boolean
     */
    public function authRequired();

    /**
     * Execute the Endpoint Object
     * @return self
     */
    public function execute();


    /**
     * Get the options configured on the Endpoint
     * @return array
     */
    public function getOptions();

    /**
     * Get the full URL being used by the Endpoint
     * @return string
     */
    public function getUrl();

    /**
     * Get the data URL being used by the Endpoint
     * @return string
     */
    public function getData();

    /**
     * Get the Response from the Endpoint request
     * @return ResponseInterface
     */
    public function getResponse();

    /**
     * Get the Request Object being used by the Endpoint
     * @return RequestInterface
     */
    public function getRequest();

}