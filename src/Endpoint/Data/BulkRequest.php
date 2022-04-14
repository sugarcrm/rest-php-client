<?php

/**
 * ©[2022] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace Sugarcrm\REST\Endpoint\Data;

use GuzzleHttp\Psr7\Request;
use MRussell\REST\Endpoint\Data\AbstractEndpointData;
use Sugarcrm\REST\Endpoint\SugarEndpointInterface;

/**
 * Provides a DataInterface for managing the Bulk API request payload
 *   - Compiles Endpoint Objects into Bulk request data
 *   - Compiled Request objects into Bulk request data
 *   - Allows for manual bulk request data
 * @package Sugarcrm\REST\Endpoint\Data
 */
class BulkRequest extends AbstractEndpointData {
    const BULK_REQUEST_DATA_NAME = 'requests';

    /**
     * Convert the Current Data Array to the Bulk Request
     * @param boolean $compile
     * @return array
     */
    public function toArray($compile = true): array {
        $data = parent::toArray(true);
        if ($compile) {
            $compiled = array(
                self::BULK_REQUEST_DATA_NAME => array()
            );
            if (isset($data[self::BULK_REQUEST_DATA_NAME])) {
                $compiled[self::BULK_REQUEST_DATA_NAME] = $data[self::BULK_REQUEST_DATA_NAME];
            }
            foreach ($data as $key => $value) {
                if ($key === self::BULK_REQUEST_DATA_NAME) {
                    continue;
                }
                if (is_array($value)) {
                    $compiled[self::BULK_REQUEST_DATA_NAME][] = $value;
                    continue;
                }
                if (is_object($value)) {
                    if ($value instanceof SugarEndpointInterface) {
                        $request = $value->compileRequest();
                    } else {
                        $request = $value;
                    }
                    if ($request instanceof Request) {
                        $request = $this->extractRequest($request);
                        if ($request) {
                            $compiled[self::BULK_REQUEST_DATA_NAME][] = $request;
                        }
                    }
                }
            }
            return $compiled;
        }
        return $data;
    }

    /**
     * Extract a Guzzle Request object into Sugar bulk request payload
     * @param Request $Request
     * @return array
     */
    protected function extractRequest(Request $Request) {
        $url = $Request->getUri()->getPath();
        if (empty($url)) {
            return false;
        }
        $urlArray = explode("/rest/", $url);
        return array(
            'url' => "/" . $urlArray[1],
            'method' => $Request->getMethod(),
            'headers' => $this->normaliseHeaders($Request->getHeaders()),
            'data' => $Request->getBody()->getContents()
        );
    }

    /**
     * Normalize the headers into standard array of strings `Header: Value`
     * @param array $headers
     * @return array
     */
    private function normaliseHeaders(array $headers): array {
        $normalisedHeaders = [];
        foreach ($headers as $name => $values) { 
            foreach ($values as $value) { 
                $normalisedHeaders[] = sprintf('%s: %s', $name, $value);
            }
        }
        return $normalisedHeaders;
    }
    
}
