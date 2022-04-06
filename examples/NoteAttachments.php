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
    $SugarAPI->getHandlerStack()->push(Middleware::history($history),'history');
    try {
        if ($SugarAPI->login()){
            echo "Logged In: ";
            pre($SugarAPI->getAuth()->getToken());
            $Note = $SugarAPI->Note()->set("name", "Test");
            echo "Creating Note with multiple attachments: ";
            $Note->multiAttach([
                $file,
                [
                    'path' => $file,
                    'name' => 'foobar.txt'
                ],
                [
                    'path' => $file,
                    'name' => 'another.txt'
                ]
            ]);
            echo "Saved Note ID: {$Note['id']}<br>";
            $Note->addField('attachment_list');
            $Note->retrieve();
            pre($Note->attachment_list);
        } else {
            echo "Could not login.";
            pre($SugarAPI->getAuth()->getActionEndpoint('authenticate')->getResponse());
        }
    } catch (Exception $ex) {
        echo "Error Occurred: ";
        pre($ex->getMessage());
    } finally {
        foreach($history as $item){
            if (isset($item['request'])){
                pre($item['request']->getBody()->getContents());
            }
        }
    }
} else {
    if (!file_exists($file)){
        echo "Test file does not exist. Create/upload $file";
    }
    if (!is_readable($file)){
        echo "Test file is not readable for upload. Fix permissions and try again.";
    }
}