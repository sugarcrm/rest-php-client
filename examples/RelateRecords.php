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
        $Account = $SugarAPI->module('Accounts')->set("name","Relate Records Test");
        echo "Creating Account: ";
        pre($Account->asArray());
        $Account->save();
        pre("Saved Account ID: {$Account['id']}");
        $Opportunity = $SugarAPI->module('Opportunities');
        $Opportunity['name'] = 'Test Opportunity';
        $Opportunity['description'] = 'This opportunity was created via the SugarCRM REST API Client v2 to test creating relationships.';
        echo "Creating Opportunity: ";
        pre($Account->asArray());
        $Opportunity->save();
        pre("Saved Opportunity ID: {$Opportunity['id']}");

        echo "Relating Opportunity to Account: ";
        $Account->relate('opportunities',$Opportunity['id']);
        echo "Request: ";
        pre($Account->getRequest()->getBody());
        echo "Response: ";
        pre($Account->getResponse()->getBody());
    } else {
        echo "Could not login.";
        pre($SugarAPI->getAuth()->getActionEndpoint('authenticate')->getResponse());
    }

}catch (Exception $ex){
    echo "Error Occurred: ";
    pre($ex->getMessage());
    pre($ex->getTraceAsString());
}