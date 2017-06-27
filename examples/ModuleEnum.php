<?php
/**
 * ©[2017] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */
require_once 'include.php';

$SugarAPI = new \Sugarcrm\REST\Client\Sugar7API($server,$credentials);

try{
    $SugarAPI->login();
    echo "Logged In: <pre>";
    echo $SugarAPI->getAuth()->getToken()->access_token;
    echo "</pre>";
    $Enum = $SugarAPI->enum('Accounts','account_type');
    $response = $Enum->execute()->getResponse();
    echo "<pre> Account Type options: <br>".print_r($response->getBody(),true)."</pre>";
}catch (Exception $ex){
    echo "<pre>";
    //print_r($SugarAPI);
    echo "</pre>";
    print $ex->getMessage();
}