<?php
/**
 * ©[2019] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

require_once __DIR__ . '/../vendor/autoload.php';

$server = 'localhost';
$credentials = array(
    'username' => 'admin',
    'password' => 'asdf',
    'platform' => 'base'
);

function pre($message)
{
    $msg = $message;
    if (!is_string($message)){
        $msg = print_r($message,true);
    }
    echo "<pre>$msg</pre><br/>";
}