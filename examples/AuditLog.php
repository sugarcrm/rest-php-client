<?php

require_once 'include.php';

$SugarAPI = new \Sugarcrm\REST\Client\Sugar7API($server,$credentials);
try{
    $SugarAPI->login();
    echo "Logged In: <pre>";
    print_r($SugarAPI->getAuth()->getToken()->access_token);
    echo "</pre>";
    $Account = $SugarAPI->module('Accounts')->set("name","Audit Log Test");
    $Account->save();
    echo "<pre>Created Account: {$Account['id']}</pre><br>";
    $Account->set('phone_office','555-555-5555');
    $Account['name'] = 'Audit Log Test - Updated';
    $Account['assigned_user_id'] = 'seed_max_id';
    $Account->save();
    echo "<pre> Account Updated: <br>".print_r($Account->asArray(),true)."</pre><br>";
    $Account->audit();
    echo "<pre> Audit Log:".print_r($Account->getResponse()->getBody(),true)."</pre><br>";
}catch (Exception $ex){
    echo "<pre>";
    //print_r($SugarAPI);
    echo "</pre>";
    print $ex->getMessage();
}