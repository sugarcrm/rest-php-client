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
        $Account = $SugarAPI->module('Accounts')->set("name","Test")->set("phone_office","555-555-5555");
        echo "Creating Account: ";
        pre($Account->toArray());
        $Account->save();
        pre("Saved Account ID: {$Account['id']}");
        $Account->set('employees','100');
        $Account['shipping_address_city'] = 'Indianapolis';
        echo "Changing fields employees and shipping address...<br>";
        $Account->save();
        echo "Account Updated: ";
        pre($Account->toArray());
        $Account2 = $SugarAPI->module('Accounts',$Account['id']);
        $Account2->retrieve();
        echo "Retrieving the Account Again...<br>";
        pre($Account2->toArray());
        $Account2->delete();
        echo "Account Deleted. Response: ";
        pre($Account2->getResponseBody());
    } else {
        echo "Could not login.";
        pre($SugarAPI->getAuth()->getActionEndpoint('authenticate')->getResponse());
    }
}catch (Exception $ex){
    echo "Error Occurred: ";
    pre($ex->getMessage());
    pre($ex->getTraceAsString());
}
