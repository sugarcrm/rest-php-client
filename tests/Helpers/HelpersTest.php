<?php
/**
 * ©[2016] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace SugarAPI\SDK\Tests\Helpers;

use SugarAPI\SDK\Helpers\Helpers;

/**
 * Class HelpersTest
 * @package SugarAPI\SDK\Tests\Helpers
 * @coversDefaultClass SugarAPI\SDK\Helpers\Helpers
 */
class HelpersTest extends \PHPUnit_Framework_TestCase {

    public static function setUpBeforeClass()
    {
    }

    public static function tearDownAfterClass()
    {
    }

    public function setUp()
    {
        parent::setUp();
    }

    public function tearDown()
    {
        parent::tearDown();
    }

    /**
     * @test
     * @covers ::configureAPIURL
     * @group default
     */
    public function testConfigureAPIUrl()
    {
        $this->assertEquals('http://localhost/rest/v10/',Helpers::configureAPIURL('localhost'));
        $this->assertEquals('https://localhost/rest/v10/',Helpers::configureAPIURL('https://localhost'));
        $this->assertEquals('http://localhost/rest/v10/',Helpers::configureAPIURL('localhost/rest/v10'));
        $this->assertEquals('http://localhost/rest/v10/',Helpers::configureAPIURL('localhost/rest/v10/'));
        $this->assertEquals('http://localhost/rest/v10/',Helpers::configureAPIURL('http://localhost/rest/v10/'));
        $this->assertEquals('http://localhost/rest/v10/',Helpers::configureAPIURL('http://localhost/'));
        $this->assertEquals('https://localhost/rest/v10/',Helpers::configureAPIURL('https://localhost/rest/v10'));
        $this->assertEquals('https://localhost/rest/v10/',Helpers::configureAPIURL('https://localhost/rest/v10/'));
        $this->assertEquals('http://localhost/rest/v10/',Helpers::configureAPIURL('http://localhost/rest/v10/rest/v10/rest/v10'));
        $this->assertEquals('http://localhost/rest/v10/',Helpers::configureAPIURL('localhost','10'));
        $this->assertEquals('http://localhost/rest/v10/',Helpers::configureAPIURL('localhost',10));
        $this->assertEquals('http://localhost/rest/v11/',Helpers::configureAPIURL('localhost',11));
        $this->assertEquals('https://localhost/rest/v10/',Helpers::configureAPIURL('https://localhost','10'));
        $this->assertEquals('https://localhost/rest/v10/',Helpers::configureAPIURL('https://localhost',10));
        $this->assertEquals('https://localhost/rest/v11/',Helpers::configureAPIURL('https://localhost',11));
        $this->assertEquals('http://localhost/rest/v10/',Helpers::configureAPIURL('localhost/rest/v10','10'));
        $this->assertEquals('http://localhost/rest/v10/',Helpers::configureAPIURL('localhost/rest/v10',10));
        $this->assertEquals('http://localhost/rest/v11/',Helpers::configureAPIURL('localhost/rest/v10',11));
        $this->assertEquals('http://localhost/rest/v10/',Helpers::configureAPIURL('localhost/rest/v10/','10'));
        $this->assertEquals('http://localhost/rest/v10/',Helpers::configureAPIURL('localhost/rest/v10/',10));
        $this->assertEquals('http://localhost/rest/v11/',Helpers::configureAPIURL('localhost/rest/v10/',11));
        $this->assertEquals('http://localhost/rest/v10/',Helpers::configureAPIURL('http://localhost/rest/v10/','10'));
        $this->assertEquals('http://localhost/rest/v10/',Helpers::configureAPIURL('http://localhost/rest/v10/',10));
        $this->assertEquals('http://localhost/rest/v11/',Helpers::configureAPIURL('http://localhost/rest/v10/',11));
        $this->assertEquals('http://localhost/rest/v10/',Helpers::configureAPIURL('http://localhost/','10'));
        $this->assertEquals('http://localhost/rest/v10/',Helpers::configureAPIURL('http://localhost/',10));
        $this->assertEquals('http://localhost/rest/v11/',Helpers::configureAPIURL('http://localhost/',11));
        $this->assertEquals('https://localhost/rest/v10/',Helpers::configureAPIURL('https://localhost/rest/v10','10'));
        $this->assertEquals('https://localhost/rest/v10/',Helpers::configureAPIURL('https://localhost/rest/v10',10));
        $this->assertEquals('https://localhost/rest/v11/',Helpers::configureAPIURL('https://localhost/rest/v10',11));
        $this->assertEquals('https://localhost/rest/v10/',Helpers::configureAPIURL('https://localhost/rest/v10/','10'));
        $this->assertEquals('https://localhost/rest/v10/',Helpers::configureAPIURL('https://localhost/rest/v10/',10));
        $this->assertEquals('https://localhost/rest/v11/',Helpers::configureAPIURL('https://localhost/rest/v10/',11));
        $this->assertEquals('http://localhost/rest/v10/',Helpers::configureAPIURL('http://localhost/rest/v10/rest/v10/rest/v10','10'));
        $this->assertEquals('http://localhost/rest/v10/',Helpers::configureAPIURL('http://localhost/rest/v10/rest/v10/rest/v10',10));
        $this->assertEquals('http://localhost/rest/v11/',Helpers::configureAPIURL('http://localhost/rest/v10/rest/v10/rest/v10',11));
        $this->assertEquals('https://localhost/SugarTest/rest/v11/',Helpers::configureAPIURL('https://localhost/SugarTest/rest/v10/',11));
        $this->assertEquals('http://localhost/Sugar/Test/rest/v10/',Helpers::configureAPIURL('http://localhost/Sugar/Test/rest/v10/rest/v10/rest/v10','10'));
        $this->assertEquals('http://localhost/SugarTest/rest/v10/',Helpers::configureAPIURL('http://localhost/SugarTest/rest/v10/rest/v10/rest/v10',10));
        $this->assertEquals('http://localhost/SugarTest/rest/v11/',Helpers::configureAPIURL('http://localhost/SugarTest/rest/v10/rest/v10/rest/v10',11));
        $this->assertEquals('http://localhost/SugarTest/rest/v11_1/',Helpers::configureAPIURL('http://localhost/SugarTest/rest/v10/rest/v10/rest/v10','11_1'));

    }

    /**
     * @test
     * @covers ::getSDKEndpointRegistry
     * @group default
     */
    public function testGetSDKEndpointRegistry(){
        $this->assertNotEmpty(Helpers::getSDKEndpointRegistry());
    }
}
