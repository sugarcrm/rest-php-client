<?php

require_once 'include.php';

$SugarAPI = new \Sugarcrm\REST\Client\Sugar7API($server,$credentials);
try{
    $SugarAPI->login();
    echo "<pre>";
    print_r($SugarAPI->getAuth()->getToken()->access_token);
    echo "</pre>";
    $Accounts = $SugarAPI->list('Accounts');
    $Accounts->filter()->and()
                            ->or()
                                ->starts('name','s')
                                ->contains('name','test')
                            ->endOr()
                            ->equals('assigned_user_id','seed_max_id')
                        ->endAnd();
    echo "<pre> Filter Accounts that are assigned to User Max, and that either start with an S or contain 'test' in the name: ".var_dump($Accounts->filter()->compile())."</pre><br>";
    $Accounts->filter()->execute();
    echo "<pre> Response:".print_r($Accounts->getResponse()->getBody(),true)."</pre><br>";
}catch (Exception $ex){
    echo "<pre>";
    //print_r($SugarAPI);
    echo "</pre>";
    print $ex->getMessage();
}