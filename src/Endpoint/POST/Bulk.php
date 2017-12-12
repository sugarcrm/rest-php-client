<?php
/**
 * ©[2016] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace SugarAPI\SDK\Endpoint\POST;

use SugarAPI\SDK\Endpoint\Abstracts\POST\AbstractPostEndpoint;

class Bulk extends AbstractPostEndpoint
{
    /**
     * @inheritdoc
     */
    protected $_URL = 'bulk';

    /**
     * @inheritdoc
     */
    protected $_DATA_TYPE = 'array';

    /**
     * @inheritdoc
     */
    protected $_REQUIRED_DATA = array(
        'requests' => null
    );

    private $bulkRequest = array(
        'url' => '',
        'data' => '',
        'headers' => array(),
        'method' => ''
    );

    /**
     * @inheritdoc
     * @param $data
     * If array of Endpoint Interfaces are passed in, it will cover that to the proper Data array for the Bulk API
     */
    protected function configureData($data)
    {
        if (!isset($data['requests'])) {
            $requestData = array(
                'requests' => array()
            );
            $counter = 0;
            foreach ($data as $key => $Endpoint) {
                if (is_object($Endpoint)) {
                    $requestData['requests'][$counter] = $this->bulkRequest;
                    $requestData['requests'][$counter]['method'] = $Endpoint->getRequest()->getType();
                    $requestData['requests'][$counter]['url'] = "v10/" . str_replace($this->baseUrl, "", $Endpoint->getUrl());

                    if ($requestData['requests'][$counter]['method'] == "POST" || $requestData['requests'][$counter]['method'] == "PUT") {
                        $requestData['requests'][$counter]['data'] = json_encode($Endpoint->getData());
                    } else {

                        if ($requestData['requests'][$counter]['method'] == "GET" && $Endpoint->getData()){
                            $requestData['requests'][$counter]['url'] .= '?' . http_build_query($Endpoint->getData());
                        }

                        unset($requestData['requests'][$counter]['data']);
                    }
                    $requestData['requests'][$counter]['headers'] = $Endpoint->getRequest()->getHeaders();

                    $counter++;
                }
            }
            $data = $requestData;
        }
        parent::configureData($data);
    }
}
