<?php

require_once 'include.php';
$file = __DIR__.'/test.txt';

if (file_exists($file) && is_readable($file)) {
    $SugarAPI = new \Sugarcrm\REST\Client\Sugar7API($server, $credentials);
    try {
        $SugarAPI->login();
        echo "<pre>";
        print_r($SugarAPI->getAuth()->getToken()->access_token);
        echo "</pre>";
        $Note = $SugarAPI->module('Notes')->set("name", "Test");
        echo "<pre> Note:" . print_r($Note->asArray(), true) . "</pre><br>";
        $Note->save();
        echo "<pre> Saved Note ID: {$Note['id']}</pre><br>";
        echo "Attempting to attach $file";
        $Note->attachFile('filename', $file,TRUE,'text/plain','testtest.txt');
        $response = $Note->getResponse()->getBody();
        //echo "<pre>" . print_r($Note->getRequest(), true) . "</pre>";
        echo "File uploaded: <pre>" . print_r($response,true) . "</pre>";

        $Note = $SugarAPI->module('Notes');
        $Note->tempFile('filename',$file);
        $response = $Note->getResponse()->getBody();
        echo "<pre>" . print_r($Note->getRequest(), true) . "</pre>";
        echo "File uploaded: <pre>" . print_r($response,true) . "</pre>";
        $Note->set('name','This is a test');
        $Note->save();
        echo "<pre> Note ID: {$Note['id']}</pre><br>";
        echo "<pre>" . print_r($Note->getRequest(), true) . "</pre>";
    } catch (Exception $ex) {
        echo "<pre>";
        //print_r($SugarAPI);
        echo "</pre>";
        print $ex->getMessage();
    }
} else {
    if (!file_exists($file)){
        echo "Test file does not exist. Create/upload $file";
    }
    if (!is_readable($file)){
        echo "Test file is not readable for upload. Fix permissions and try again.";
    }
}