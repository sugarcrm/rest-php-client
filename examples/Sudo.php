<?php

require_once 'include.php';

$SugarAPI = new \Sugarcrm\REST\Client\Sugar7API($server,$credentials);
try{
    $SugarAPI->login();
    echo "Logged In: <pre>";
    print_r($SugarAPI->getAuth()->getToken());
    echo "</pre>";
    if ($SugarAPI->sudo('will')){
        echo "Sudo'd to will:".print_r($SugarAPI->getAuth()->getToken(),true);
    }
}catch (Exception $ex){
    echo "<pre>";
    //print_r($SugarAPI);
    echo "</pre>";
    print $ex->getMessage();
}