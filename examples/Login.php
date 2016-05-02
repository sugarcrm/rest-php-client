<?php

require_once __DIR__.'/../vendor/autoload.php';

try {
    $SugarAPI = new \SugarAPI\SDK\SugarAPI('instances.this/Ent/7621/', array(
        'username' => 'admin',
        'password' => 'asdf'
    ));
    $SugarAPI->login();
    print_r($SugarAPI->getToken());
    echo "<br>Test1<br>";
    print_r(\SugarAPI\SDK\SugarAPI::getStoredToken('sugar'));


    echo "<br>Test2<br>";
    $SugarAPI2 = new \SugarAPI\SDK\SugarAPI('instances.this/Ent/7621/', array(
        'username' => 'admin',
        'password' => 'asdf'
    ));
    if ($SugarAPI2->authenticated()) {
        echo "Authenticated!";
        print_r($SugarAPI2->getToken());
    }
} catch(\SugarAPI\SDK\Exception\SDKException $ex){
    print_r($ex->getMessage());
}