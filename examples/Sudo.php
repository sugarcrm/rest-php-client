<?php
/**
 * ©[2022] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

require_once 'include.php';

$SugarAPI = new \Sugarcrm\REST\Client\Sugar7API($server,$credentials);
try{
    if ($SugarAPI->login()){
        echo "Logged In: ";
        pre($SugarAPI->getAuth()->getToken());
        if ($SugarAPI->sudo('will')){
            echo "Sudo'd to will:<br>";
            echo "Token:";
            pre($SugarAPI->getAuth()->getToken());
            echo "User:";
            $Me = $SugarAPI->me();
            pre($Me->asArray());
        }
    } else {
        echo "Could not login.";
        pre($SugarAPI->getAuth()->getActionEndpoint('authenticate')->getResponse());
    }
}catch (Exception $ex){
    echo "Error Occurred: ";
    pre($ex->getMessage());
    pre($ex->getTraceAsString());
}