<?php

require_once __DIR__ . '/../vendor/autoload.php';

try{
    $SugarAPI = new \SugarAPI\SDK\SugarAPI('instances.this/Pro/7621/',array('username' => 'admin','password'=>'asdf'));
    $SugarAPI->login();
    $EP = $SugarAPI->createRecord('Accounts');
    $data = array(
        'name' => 'Test Record 4',
        'email1' => 'test4@sugar.com'
    );
    $response = $EP->execute($data)->getResponse();
    if ($response->getStatus()=='200'){
        $record = $response->getBody(false);
        $EP2 = $SugarAPI->getRecord('Accounts',$record->id)->execute(array('fields' => 'name,email1'));
        $getResponse = $EP2->getResponse();
        print $EP2->getUrl();
        print_r($getResponse->getBody());
    }

}catch(\SugarAPI\SDK\Exception\AuthenticationException $ex){
    print $ex->getMessage();
}