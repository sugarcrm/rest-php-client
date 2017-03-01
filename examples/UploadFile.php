<?php

require_once __DIR__.'/../vendor/autoload.php';

try{
    $SugarAPI = new \SugarAPI\SDK\SugarAPI('instances.this/Ent/7800/', array('username' => 'admin', 'password'=>'asdf'));
    $SugarAPI->login();
    $EP = $SugarAPI->createRecord('Notes')->execute(array('name' => 'Test Note'));
    $response = $EP->getResponse();
    if ($response->getStatus()=='200'){
        $record = $response->getBody(false);

        $upload = $SugarAPI->attachFile('Notes', $record->id, 'filename')->execute(__DIR__.'/testfile.txt');
        $response = $upload->getResponse();
        if ($response->getStatus()=='200'){
            $record = $response->getBody();
            print_r($record);
        }else{
            print_r($upload->getRequest());
            echo "Failed to Update Note with File<br>";
            echo "Response: ".$response->getStatus()."<br>";
            print_r($response->getBody());
        }
    }else{
        echo "Failed to create Note<br>";
        echo "Response: ".$response->getStatus()."<br>";
        print_r($response->getBody());
    }

}catch (\SugarAPI\SDK\Exception\SDKException $ex){
    print $ex->getMessage();
}