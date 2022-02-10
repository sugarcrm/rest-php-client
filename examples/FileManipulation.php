<?php

require_once 'include.php';
$file = __DIR__.'/test.txt';

if (file_exists($file) && is_readable($file)) {
    $SugarAPI = new \Sugarcrm\REST\Client\Sugar7API($server, $credentials);
    try {
        if ($SugarAPI->login()){
            echo "Logged In: ";
            pre($SugarAPI->getAuth()->getToken());
            $Note = $SugarAPI->module('Notes')->set("name", "Test");
            echo "Creating Note: ";
            pre($Note->asArray());
            $Note->save();
            echo "Saved Note ID: {$Note['id']}<br>";
            echo "Attempting to attach $file...";
            $Note->attachFile('filename', $file,true,'text/plain','testtest.txt');
            $response = $Note->getResponse()->getBody();
            //echo "<pre>" . print_r($Note->getRequest(), true) . "</pre>";
            echo "File uploaded: ";
            pre($response);

            $Note = $SugarAPI->module('Notes');
            echo "Uploading temp file for new note...";
            $Note->tempFile('filename',$file);
            $response = $Note->getResponse()->getBody();
            pre($Note->getRequest());
            echo "File uploaded: ";
            pre($response);
            $Note->set('name','This is a test');
            $Note->save();
            echo "Note ID: {$Note['id']}<br>";
            pre($Note->getRequest());
        } else {
            echo "Could not login.";
            pre($SugarAPI->getAuth()->getActionEndpoint('authenticate')->getResponse());
        }
    } catch (Exception $ex) {
        echo "Error Occurred: ";
        pre($ex->getMessage());
    }
} else {
    if (!file_exists($file)){
        echo "Test file does not exist. Create/upload $file";
    }
    if (!is_readable($file)){
        echo "Test file is not readable for upload. Fix permissions and try again.";
    }
}