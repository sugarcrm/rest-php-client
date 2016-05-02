[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%205.3-8892BF.svg)](https://php.net/)
#SugarCRM REST PHP Client#

##Overview##
A simple and intuitive Library for accessing a Sugar 7's REST v10 API. Allows for Object Oriented design around accessing data from a SugarCRM system, so you can easily get your integration project underway.

##Usage
###Composer
You can easily add the Library to a project, by adding the Package to your composer.json file.
<pre>
    "require": {
        "sugarcrm/rest-php-client": '1.0.*'
    },
</pre>

###Code
With the library included in your project, you can instantiate the included SugarAPI object as follows, by passing in your SugarCRM Server and Authentication Credentials like so:
<pre>
  $instance = 'localhost/sugarcrm/';
  $authOptions = array(
            'username' => 'user',
            'password' => 'pass'
  );
  $SugarAPI = new \SugarAPI\SDK\SugarAPI($instance,$authOptions);
  $SugarAPI->login();
</pre>
Once you have the Object setup, you can call the various methods that related to the SugarCRM REST v10 API Endpoints.
See examples directory for a few examples of manipulating data with the SDK, otherwise some brief code snippets below showcase the current available methods for use.

##Current API Methods
###CRUD Methods
- Create Module Records
 - \<module\> - POST
 <pre>
    $SugarAPI = new \SugarAPI\SDK\SugarAPI();
    $SugarAPI->login();
    $SugarAPI->createRecord($module)->execute($record);
 </pre>
- Read Module Records
 - \<module\>/:record - GET
 <pre>
    $SugarAPI = new \SugarAPI\SDK\SugarAPI();
    $SugarAPI->login();
    $SugarAPI->getRecord($module,$recordID)->execute();
 </pre>
- List Module Records, and Filter Records using the Filter API
 - \<module\>/filter - POST
 <pre>
    $SugarAPI = new \SugarAPI\SDK\SugarAPI();
    $SugarAPI->login();
    $SugarAPI->filterRecords($module)->execute($filterParams);
  </pre>
- Update Module Records
 - \<module\>/:record - PUT
 <pre>
    $SugarAPI = new \SugarAPI\SDK\SugarAPI();
    $SugarAPI->login();
    $SugarAPI->updateRecord($module,$recordID)->execute($updatedData);
 </pre>
- Delete Module Records
 - \<module\>/:record - DELETE
 <pre>
    $SugarAPI = new \SugarAPI\SDK\SugarAPI();
    $SugarAPI->login();
    $SugarAPI->deleteRecord($module,$recordID)->execute();
 </pre>
 
###Authentication
- Login
 - oauth2/token - POST
  <pre>
    $SugarAPI = new \SugarAPI\SDK\SugarAPI();
    $SugarAPI->oauth2Token()->execute($loginParams);
  </pre>
- Refresh Token
 - oauth2/token - POST
  <pre>
    $SugarAPI = new \SugarAPI\SDK\SugarAPI();
    $SugarAPI->oauth2Refresh()->data($loginParams)->execute();
  </pre>
- Logout
 - oauth2/logout - POST
  <pre>
    $SugarAPI = new \SugarAPI\SDK\SugarAPI();
    $SugarAPI->oauth2Logout()->data($loginParams)->execute();
  </pre>
  
###User
- Me
 - me/ - GET
 <pre>
     $SugarAPI = new \SugarAPI\SDK\SugarAPI();
     $SugarAPI->login();
     $SugarAPI->me()->execute();
  </pre>
  
###Relationships
- Create Related
 - \<module\>/:record/link/:relationship - POST
 <pre>
      $SugarAPI = new \SugarAPI\SDK\SugarAPI();
      $SugarAPI->login();
      $SugarAPI->createRelated('Account',$recordID,'contacts')->execute($contactRecord);
  </pre>
- Filter Related Records
 - \<module\>/:record/link/:relationship - GET
 <pre>
      $SugarAPI = new \SugarAPI\SDK\SugarAPI();
      $SugarAPI->login();
      $SugarAPI->filterRelated('Accounts',$recordID,'contacts')->execute($filters);
  </pre>
- Get Related Record
 - \<module\>/:record/link/:relationship/:record_id - GET
 <pre>
      $SugarAPI = new \SugarAPI\SDK\SugarAPI();
      $SugarAPI->login();
      $SugarAPI->getRelated('Accounts',$recordID,'contacts',$contactId)->execute();
  </pre>
- Relate Records
 - \<module\>/:record/link/:relationship/:record_id - POST
 <pre>
      $SugarAPI = new \SugarAPI\SDK\SugarAPI();
      $SugarAPI->login();
      $SugarAPI->linkRecords('Accounts',$recordID,'contacts',$contactId)->execute();
  </pre>
- Unlink Records
 - \<module\>/:record/link/:relationship/:record_id - DELETE
 <pre>
      $SugarAPI = new \SugarAPI\SDK\SugarAPI();
      $SugarAPI->login();
      $SugarAPI->unlinkRecords('Accounts',$recordID,'contacts',$contactId)->execute();
  </pre>
  
###Global Search
- Search Globally
 - search - GET
 <pre>
      $SugarAPI = new \SugarAPI\SDK\SugarAPI();
      $SugarAPI->login();
      $SugarAPI->search()->execute($search);
  </pre>

###Bulk API
- bulk - POST
 <pre>
      $SugarAPI = new \SugarAPI\SDK\SugarAPI('instances.this/Pro/7700/',array('username' => 'admin','password'=>'asdf'));
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
  </pre>

###File Manipulation
- Upload Files to records, such as Note Records
 - \<module\>/:record/file/:field - POST
 <pre>
     $SugarAPI = new \SugarAPI\SDK\SugarAPI();
     $SugarAPI->login();
     $SugarAPI->attachFile('Notes',$recordID,'filename')->execute('/path/to/file');
 </pre>
- Get Files from records, such as Note Records
 - \<module\>/:record/file/:field - GET
 <pre>
    $SugarAPI = new \SugarAPI\SDK\SugarAPI();
    $SugarAPI->login();
    $SugarAPI->getAttachment('Notes',$recordID,'filename')->execute();
 </pre>
- Delete Files from records, such as Note Records
 - \<module\>/:record/file/:field - GET
 <pre>
    $SugarAPI = new \SugarAPI\SDK\SugarAPI();
    $SugarAPI->login();
    $SugarAPI->deleteFile('Notes',$recordID,'filename')->execute();
 </pre>
 
###Other Methods
- Favorite Records
 - \<module\>/:record/favorite - PUT
 <pre>
     $SugarAPI = new \SugarAPI\SDK\SugarAPI();
     $SugarAPI->login();
     $SugarAPI->favorite($module,$recordID)->execute();
 </pre>
- UnFavorite Records
 - \<module\>/:record/favorite - DELETE
 <pre>
     $SugarAPI = new \SugarAPI\SDK\SugarAPI();
     $SugarAPI->login();
     $SugarAPI->unfavorite($module,$recordID)->execute();
 </pre>
- Ping
 - ping - GET
 <pre>
     $SugarAPI = new \SugarAPI\SDK\SugarAPI();
     $SugarAPI->login();
     $SugarAPI->ping()->execute();
 </pre>

## Developing the PHP REST Client

#### Requirement:
You need to have installed: [php 5.3+](https://php.org/)

#### Steps:
1. With PHP installed, navigate to the repo folder (rest-php-client) via terminal.
2. Run 'composer install'

### Contributing:
See [CONTRIBUTING](CONTRIBUTING.md) for how you can contribute changes back into this project.


