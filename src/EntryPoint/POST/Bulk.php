<?php
/**
 * ©[2016] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace SugarAPI\SDK\EntryPoint\POST;

use SugarAPI\SDK\EntryPoint\Abstracts\POST\AbstractPostEntryPoint;

class Bulk extends AbstractPostEntryPoint {

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
        'requests' => NULL
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
     * If array of EntryPoint Interfaces are passed in, it will cover that to the proper Data array for the Bulk API
     */
    protected function configureData($data) {
        if (!isset($data['requests'])) {
            $requestData = array(
                'requests' => array()
            );
            $counter = 0;
            foreach ($data as $key => $EntryPoint) {
                if (is_object($EntryPoint)) {
                    $requestData['requests'][$counter] = $this->bulkRequest;
                    $requestData['requests'][$counter]['method'] = $EntryPoint->getRequest()->getType();
                    if ($requestData['requests'][$counter]['method'] == "POST" || $requestData['requests'][$counter]['method'] == "PUT") {
                        $requestData['requests'][$counter]['data'] = json_encode($EntryPoint->getData());
                    } else {
                        unset($requestData['requests'][$counter]['data']);
                    }
                    $requestData['requests'][$counter]['headers'] = $EntryPoint->getRequest()->getHeaders();
                    $requestData['requests'][$counter]['url'] = "v10/" . str_replace($this->baseUrl, "", $EntryPoint->getUrl());

                    $counter++;
                }
            }
            $data = $requestData;
        }
        parent::configureData($data);
    }
}