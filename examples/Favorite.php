<?php

require_once 'include.php';

$SugarAPI = new \Sugarcrm\REST\Client\Sugar7API($server,$credentials);

try{
    $SugarAPI->login();
    echo "Logged In: <pre>";
    print_r($SugarAPI->getAuth()->getToken()->access_token);
    echo "</pre>";
    $Account = $SugarAPI->module('Accounts')->set("name","Favorite Test");
    $Account->save();
    echo "<pre> Account Created: {$Account['id']}</pre><br>";
    $Account->favorite();
    echo "<pre> Account added to Favorites: <br>".print_r($Account,true)."</pre>";
}catch (Exception $ex){
    echo "<pre>";
    //print_r($SugarAPI);
    echo "</pre>";
    print $ex->getMessage();
}