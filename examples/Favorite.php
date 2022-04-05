<?php
/**
 * ©[2022] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

require_once 'include.php';

$SugarAPI = new \Sugarcrm\REST\Client\SugarApi($server,$credentials);

try{
    if ($SugarAPI->login()){
        echo "Logged In: <pre>";
        print_r($SugarAPI->getAuth()->getToken()->access_token);
        echo "</pre>";
        $Account = $SugarAPI->module('Accounts')->set("name","Favorite Test");
        $Account->save();
        echo "<pre> Account Created: {$Account['id']}</pre><br>";
        $Account->favorite();
        echo "<pre> Account added to Favorites: <br>".print_r($Account,true)."</pre>";
    } else {
        echo "Could not login.";
        pre($SugarAPI->getAuth()->getActionEndpoint('authenticate')->getResponse());
    }
}catch (Exception $ex){
    echo "Error Occurred: ";
    pre($ex->getMessage());
}