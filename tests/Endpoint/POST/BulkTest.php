<?php
/**
 * ©[2016] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace SugarAPI\SDK\Tests\Endpoint\POST;

use SugarAPI\SDK\Endpoint\GET\ModuleRecord;
use SugarAPI\SDK\Endpoint\POST\Bulk;
use SugarAPI\SDK\Endpoint\POST\ModuleFilter;

/**
 * Class BulkTest
 * @package SugarAPI\SDK\Tests\Endpoint\POST
 * @coversDefaultClass SugarAPI\SDK\Endpoint\POST\Bulk
 * @group entryPoints
 */
class BulkTest extends \PHPUnit_Framework_TestCase {

    public static function setUpBeforeClass()
    {
    }

    public static function tearDownAfterClass()
    {
    }

    protected $url = 'http://localhost/rest/v10/';

    public function setUp()
    {
        parent::setUp();
    }

    public function tearDown()
    {
        parent::tearDown();
    }

    /**
     * @covers ::configureData
     * @group sdkEP
     */
    public function testConfigureData(){
        $EP = new Bulk($this->url);
        $FilterAccounts = new ModuleFilter($this->url,array('Accounts'));
        $FilterAccounts->setData(array(
            'max_num' => 3
        ));
        $GetContact = new ModuleRecord($this->url,array('Contacts','1234a'));
        $data = array(
            $FilterAccounts,
            $GetContact
        );
        $EP->execute($data);

        $configuredData = array(
            'requests' => array(
                array(
                    'url' => 'v10/Accounts/filter',
                    'data' => json_encode(array('max_num' => 3)),
                    'headers' => $FilterAccounts->getRequest()->getHeaders(),
                    'method' => "POST"
                ),
                array(
                    'url' => 'v10/Contacts/1234a',
                    'headers' => $FilterAccounts->getRequest()->getHeaders(),
                    'method' => "GET"
                ),
            )
        );
        $this->assertEquals($configuredData,$EP->getData());
        unset($EP);

        $EP = new Bulk($this->url);
        $EP->setData($configuredData);
        $EP->execute();
        $this->assertEquals($configuredData,$EP->getData());
        unset($EP);

    }

}
