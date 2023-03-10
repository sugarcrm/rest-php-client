<?php
/**
 * ©[2022] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

use GuzzleHttp\Middleware;

require_once 'include.php';
$file = __DIR__.'/test.txt';

if (file_exists($file) && is_readable($file)) {
    $SugarAPI = new \Sugarcrm\REST\Client\SugarAPI($server, $credentials);
    $history = [];
    $SugarAPI->getHandlerStack()->push(Middleware::history($history), 'history');
    try {
        if ($SugarAPI->login()) {
            echo "Logged In: ";
            pre($SugarAPI->getAuth()->getToken());
            $Note = $SugarAPI->module('Notes')->set("name", "Test");
            echo "Creating Note: ";
            pre($Note->toArray());
            $Note->save();
            echo "Saved Note ID: {$Note['id']}<br>";
            echo "Attempting to attach $file...";
            $Note->attachFile('filename', $file, true, 'testtest.txt');
            $response = $Note->getResponseBody();
            //echo "<pre>" . print_r($Note->getRequest(), true) . "</pre>";
            echo "File uploaded: ";
            pre($response);

            $Note = $SugarAPI->module('Notes');
            echo "Uploading temp file for new note...";
            $Note->tempFile('filename', $file);
            $response = $Note->getResponseBody();
            echo "File uploaded: ";
            pre($response);
            $Note->set('name', 'This is a test');
            $Note->save();
            echo "Note ID: {$Note['id']}<br>";
        } else {
            echo "Could not login.";
            pre($SugarAPI->getAuth()->getActionEndpoint('authenticate')->getResponse());
        }
    } catch (Exception $ex) {
        echo "Error Occurred: ";
        pre($ex->getMessage());
    } finally {
        foreach ($history as $item) {
            if (isset($item['request'])) {
                pre($item['request']->getBody()->getContents());
            }
        }
    }
} else {
    if (!file_exists($file)) {
        echo "Test file does not exist. Create/upload $file";
    }
    if (!is_readable($file)) {
        echo "Test file is not readable for upload. Fix permissions and try again.";
    }
}
