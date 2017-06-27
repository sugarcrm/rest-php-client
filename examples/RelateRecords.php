<?php

require_once 'include.php';

$SugarAPI = new \Sugarcrm\REST\Client\Sugar7API($server,$credentials);
try{
    $SugarAPI->login();
    echo "<pre>";
    print_r($SugarAPI->getAuth()->getToken()->access_token);
    echo "</pre>";
    $Account = $SugarAPI->module('Accounts')->set("name","Relate Records Test");
    $Account->save();
    echo "<pre> Saved Account ID: {$Account['id']}</pre><br>";
    $Opportunity = $SugarAPI->module('Opportunities');
    $Opportunity['name'] = 'Test Opportunity';
    $Opportunity['description'] = 'This opportunity was created via the SugarCRM REST API Client v2 to test creating relationships.';
    $Opportunity->save();
    echo "<pre> Opportunity Created: <br>".print_r($Opportunity->asArray(),true)."</pre>";
    $Account->relate('opportunities',$Opportunity['id']);
    echo "<pre> Relationship Created:".print_r($Account->getResponse(),true)."</pre><br>";
}catch (Exception $ex){
    echo "<pre>";
    //print_r($SugarAPI);
    echo "</pre>";
    print $ex->getMessage();
}