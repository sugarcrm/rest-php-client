<?php

require_once __DIR__ . '/../vendor/autoload.php';

try {
    $SugarAPI = new \SugarAPI\SDK\SugarAPI('instances.this/Pro/7621/', array('username' => 'admin','password'=>'asdf'));
    $SugarAPI->login();
    foreach (['Accounts', 'Contacts'] as $module) {
        $EP = $SugarAPI->count($module);
        $response = $EP->execute()->getResponse();
        if ($response->getStatus()=='200') {
            $result = $response->getBody(false);
            $count = $result->record_count;
            echo $EP->getUrl() . " <br>\n";
            echo "$count $module found. <br>\n";
        }
    }
    $SugarAPI->logout();
} catch (\SugarAPI\SDK\Exception\AuthenticationException $ex) {
    print $ex->getMessage();
}
