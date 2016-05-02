<?php

require_once __DIR__ . '/../vendor/autoload.php';

$recordID = 'ac096703-67fd-8dd2-980a-57097932f07a';

try{
    $SugarAPI = new \SugarAPI\SDK\SugarAPI('instances.this/Ent/7700/',array('username' => 'admin','password'=>'asdf'));
    $SugarAPI->login();
    $EP = $SugarAPI->getAttachment('Notes',$recordID,'filename')->downloadTo(__DIR__)->execute();
    $response = $EP->getResponse();
    if ($response->getStatus()=='200'){
        if (file_exists($response->file())){
            echo "File downloaded to ".$response->file();
        }

    }else{
        echo "Failed to retrieve Note<br>";
        echo "Response: ".$response->getStatus()."<br>";
        print_r($response->getBody());
    }

}catch(\SugarAPI\SDK\Exception\SDKException $ex){
    print $ex->getMessage();
}