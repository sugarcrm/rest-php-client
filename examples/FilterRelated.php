<?php
/**
 * ©[2017] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

require_once 'include.php';

$SugarAPI = new \Sugarcrm\REST\Client\Sugar7API($server,$credentials);
try{
    $SugarAPI->login();
    echo "<pre>";
    print_r($SugarAPI->getAuth()->getToken()->access_token);
    echo "</pre>";
    $Accounts = $SugarAPI->list('Accounts');
    $Accounts->fetch();
    $account = current($Accounts->asArray());
    $Account = $Accounts->get($account['id']);
    $Filter = $Account->filterRelated('contacts')->contains('first_name','s');
    echo "<pre> Filter Contacts related to Account {$account['id']} where first_name contains an 's': ".var_dump($Filter->compile())."</pre><br>";
    $Filter->execute();
    echo "<pre> Response:".print_r($Account->getRequest(),true)."</pre><br>";
}catch (Exception $ex){
    echo "<pre>";
    //print_r($SugarAPI);
    echo "</pre>";
    print $ex->getMessage();
}