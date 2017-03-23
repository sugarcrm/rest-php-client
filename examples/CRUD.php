<?php

require_once 'include.php';

$SugarAPI = new \Sugarcrm\REST\Client\Sugar7API($server,$credentials);
try{
    $SugarAPI->login();
    echo "<pre>";
    //print_r($SugarAPI->getAuth());
    echo "</pre>";
    $Account = $SugarAPI->module('Accounts')->set("name","Test")->set("phone_office","555-555-5555");
    echo "<pre> Account:".print_r($Account->asArray(),true)."</pre><br>";
    $Account->save();
    echo "<pre> Saved Account ID: {$Account['id']}</pre><br>";
    $Account->set('employees','100');
    $Account['shipping_address_city'] = 'Indianapolis';
    $Account->save();
    echo "<pre> Account Updated: <br>".print_r($Account->asArray(),true)."</pre>";
    $Account2 = $SugarAPI->module('Accounts',$Account['id']);
    $Account2->retrieve();
    echo "<pre> Account2:".print_r($Account2->asArray(),true)."</pre><br>";
    $Account2->delete();
    echo "Account Deleted.".print_r($Account2->getResponse(),true);
}catch (Exception $ex){
    echo "<pre>";
    //print_r($SugarAPI);
    echo "</pre>";
    print $ex->getMessage();
}
