<?php
/**
 * ©[2022] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

require_once 'include.php';

$SugarAPI = new \Sugarcrm\REST\Client\SugarApi($server,$credentials);

try{
    if ($SugarAPI->login()){
        echo "Logged In: ";
        pre($SugarAPI->getAuth()->getToken());
        $Account = $SugarAPI->module('Accounts')->set("name","DuplicateCheck Test");
        $Account->save();
        pre("Account Created: {$Account['id']}");
        $a = $Account->toArray();
        unset($a['id']);
        echo "Running duplicateCheck for Account: ";
        pre($a);
        $Account->duplicateCheck();
        pre($Account->getResponse()->getBody());
    } else {
        echo "Could not login.";
        pre($SugarAPI->getAuth()->getActionEndpoint('authenticate')->getResponse());
    }
}catch (Exception $ex){
    echo "Error Occurred: ";
    pre($ex->getMessage());
}