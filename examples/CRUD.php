<?php


require_once __DIR__ . '/../vendor/autoload.php';

try{
    $SugarAPI = new \SugarAPI\SDK\SugarAPI('instances.this/Ent/7700/',array('username' => 'admin','password'=>'asdf'));
    $SugarAPI->login();
    $EP = $SugarAPI->filterRecords('Accounts');
    $response = $EP->execute()->getResponse();
    print_r($EP->getRequest());
    if ($response->getStatus()=='200'){
        $recordList = $response->getBody(false);
        $max=count($recordList->records);
        echo "found $max records from Filter Records request. <br>";
        $number = rand(0,$max);
        $randomRecord = $recordList->records[$number];
        echo "Choose random record #$number, with ID: ".$randomRecord->id." <br>";

        $getRecord = $SugarAPI->getRecord('Accounts',$randomRecord->id)->execute(array(
            'fields' => 'name'
        ));
        $response = $getRecord->getResponse();
        if ($response->getStatus()=='200'){
            echo "Retrieved Record <br>";
            $randomRecord = $getRecord->getResponse()->getBody(false);
            $randomRecord->name = 'Updated Record Name';
            $updateRecord = $SugarAPI->updateRecord('Accounts', $randomRecord->id)->execute($randomRecord);
            $response = $updateRecord->getResponse();
            if ($response->getStatus()=='200'){
                $randomRecord = $updateRecord->getResponse()->getBody(false);
                echo "Updated Record <br>";
                print_r($randomRecord);

                $deleteRecord = $SugarAPI->deleteRecord('Accounts', $randomRecord->id)->execute();
                $response = $deleteRecord->getResponse();
                if ($response->getStatus()=='200'){
                    $response = $deleteRecord->getResponse()->getBody();
                    echo "Deleted Record <br>";
                    print_r($response);
                }else{
                    echo "Failed to Delete record<br>";
                    echo "Response: ".$response->getStatus()."<br>";
                    print_r($response->getBody());
                }
            }else{
                print_r($updateRecord->getRequest());
                echo "Failed to Update record<br>";
                echo "Response: ".$response->getStatus()."<br>";
                print_r($response->getBody());
            }
        }else{
            echo "Failed to retrieve record<br>";
            echo "Response: ".$response->getStatus()."<br>";
            print_r($response->getBody());
        }
    }else{
        echo "Response: ".$response->getStatus();
        print_r($response->getBody());
    }
}catch (\SugarAPI\SDK\Exception\AuthenticationException $ex){
    echo "Credentails:<pre>";
    print_r($SugarAPI->getCredentials());
    echo "</pre> Error Message: ";
    print $ex->getMessage();
}catch (\SugarAPI\SDK\Exception\SDKException $ex){
    echo $ex->__toString();
}
