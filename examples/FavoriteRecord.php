<?php

require_once __DIR__.'/../vendor/autoload.php';

$record_id = 'a887c75b-b89a-852e-8e67-56f1ccdee355';

try{
    $SugarAPI = new \SugarAPI\SDK\SugarAPI('localhost/ent77', array('username' => 'admin', 'password'=>'admin123'));
    $SugarAPI->login();
    $EP = $SugarAPI->favorite('Accounts', $record_id);
    $response = $EP->execute()->getResponse()->getBody();

    print_r($response);

}catch (\SugarAPI\SDK\Exception\AuthenticationException $ex){
    print $ex->getMessage();
}


