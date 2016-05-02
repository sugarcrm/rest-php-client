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
    }

    /**
     * @test
     * @covers ::getSDKVersion
     * @group default
     */
    public function testGetSDKVersion()
    {
        $this->assertEquals('1.0',Helpers::getSDKVersion());
    }

    /**
     * @test
     * @covers ::getSDKEntryPointRegistry
     * @group default
     */
    public function testGetSDKEntryPointRegistry(){
        $this->assertNotEmpty(Helpers::getSDKEntryPointRegistry());
    }
}
