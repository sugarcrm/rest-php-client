<?php

require_once __DIR__ . '/../vendor/autoload.php';

try {
    $SugarAPI = new \SugarAPI\SDK\SugarAPI('instances.this/Pro/7621/', array('username' => 'admin','password'=>'asdf'));
    $SugarAPI->login();
    $EP = $SugarAPI->duplicateCheck('Accounts');
    $data = array(
        'name' => 'Airline'
    );
    $response = $EP->execute($data)->getResponse();
    if ($response->getStatus()=='200') {
        $recordList = $response->getBody(false);
        $max=count($recordList->records);
        echo $EP->getUrl() . " <br>\n";
        echo "$max duplicate record(s) found. <br>\n";
    }
    $SugarAPI->logout();
} catch (\SugarAPI\SDK\Exception\AuthenticationException $ex) {
    print $ex->getMessage();
}
