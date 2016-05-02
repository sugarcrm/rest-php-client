<?php


require_once __DIR__ . '/../vendor/autoload.php';

try{
    $SugarAPI = new \SugarAPI\SDK\SugarAPI('instances.this/Ent/7700/',array('username' => 'admin','password'=>'asdf'));
    $SugarAPI->login();

    $Accounts = $SugarAPI->filterRecords('Accounts')->setData(array('max_num'=> 5));
    $Contacts = $SugarAPI->filterRecords('Contacts')->setData(array('max_num'=> 1));
    $Notes = $SugarAPI->filterRecords('Notes')->setData(array('max_num'=> 3));
    $Leads = $SugarAPI->filterRecords('Leads')->setData(array('max_num'=> 2));
    $BulkCall = $SugarAPI->bulk()->execute(array(
        $Accounts,
        $Contacts,
        $Notes,
        $Leads
    ));
    $response = $BulkCall->getResponse();
    if ($response->getStatus()=='200'){
        echo "<h3>Requests Completed</h3><pre>";
        print_r($response->getBody());
        echo "</pre>";
    }else{
        echo "<h3>Request Failed</h3><pre>";
        print_r($response);
        echo "</pre>";
    }

}catch (\SugarAPI\SDK\Exception\Authentication\AuthenticationException $ex){
    echo "Credentials:<pre>";
    print_r($SugarAPI->getCredentials());
    echo "</pre> Error Message: ";
    print $ex->getMessage();
}catch (\SugarAPI\SDK\Exception\SDKException $ex){
    echo $ex->__toString();
}