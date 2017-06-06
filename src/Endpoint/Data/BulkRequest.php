<?php
/**
 * ©[2016] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace Sugarcrm\REST\Endpoint\Data;

use MRussell\Http\Request\AbstractRequest;
use MRussell\REST\Endpoint\Data\AbstractEndpointData;
use Sugarcrm\REST\Endpoint\SugarEndpointInterface;

class BulkRequest extends AbstractEndpointData
{
    const BULK_REQUEST_DATA_NAME = 'requests';

    /**
     * Convert the Current Data Array to the Bulk Request
     */
    public function compile(){
        $compiled = array(
            self::BULK_REQUEST_DATA_NAME => array()
        );
        $data = $this->asArray();
        if (isset($data[self::BULK_REQUEST_DATA_NAME])){
            $compiled[self::BULK_REQUEST_DATA_NAME] = $data[self::BULK_REQUEST_DATA_NAME];
        }
        foreach($data as $key => $value){
            if ($key === self::BULK_REQUEST_DATA_NAME){
                continue;
            }
            if (is_array($value)){
                $compiled[self::BULK_REQUEST_DATA_NAME][] = $value;
                continue;
            }
            if (is_object($value)) {
                if ($value instanceof SugarEndpointInterface){
                    $request = $value->compileRequest();
                } else {
                    $request = $value;
                }
                if ($request instanceof AbstractRequest) {
                    $compiled[self::BULK_REQUEST_DATA_NAME][] = $this->extractRequest($request);
                }
            }
        }
        return $compiled;
    }

    /**
     * @param AbstractRequest $Request
     * @return array
     */
    protected function extractRequest(AbstractRequest $Request){
        $curlOptions = $Request->getCurlOptions();
        $url = $curlOptions[CURLOPT_URL];
        $urlArray = explode($url,"/rest/");
        return array(
            'url' => "/".$urlArray[1],
            'method' => $Request->getMethod(),
            'headers' => $curlOptions[CURLOPT_HEADER],
            'data' => $curlOptions[CURLOPT_POSTFIELDS]
        );
    }
}