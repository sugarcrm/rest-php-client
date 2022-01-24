<?php
/**
 * ©[2017] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace Sugarcrm\REST\Tests\Helpers;

use Sugarcrm\REST\Helpers\Helper;

/**
 * Class HelpersTest
 * @package Sugarcrm\REST\Tests\Helpers
 * @coversDefaultClass Sugarcrm\REST\Helpers\Helper
 */
class HelpersTest extends \PHPUnit\Framework\TestCase {

    public static function setUpBeforeClass(): void
    {
    }

    public static function tearDownAfterClass(): void
    {
    }

    public function setUp(): void
    {
        parent::setUp();
    }

    public function tearDown(): void
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
        $this->assertEquals('http://localhost/rest/v10/',Helper::configureAPIURL('localhost'));
        $this->assertEquals('https://localhost/rest/v10/',Helper::configureAPIURL('https://localhost'));
        $this->assertEquals('http://localhost/rest/v10/',Helper::configureAPIURL('localhost/rest/v10'));
        $this->assertEquals('http://localhost/rest/v10/',Helper::configureAPIURL('localhost/rest/v10/'));
        $this->assertEquals('http://localhost/rest/v10/',Helper::configureAPIURL('http://localhost/rest/v10/'));
        $this->assertEquals('http://localhost/rest/v10/',Helper::configureAPIURL('http://localhost/'));
        $this->assertEquals('https://localhost/rest/v10/',Helper::configureAPIURL('https://localhost/rest/v10'));
        $this->assertEquals('https://localhost/rest/v10/',Helper::configureAPIURL('https://localhost/rest/v10/'));
        $this->assertEquals('http://localhost/rest/v10/',Helper::configureAPIURL('http://localhost/rest/v10/rest/v10/rest/v10'));
        $this->assertEquals('http://localhost/rest/v10/',Helper::configureAPIURL('localhost','10'));
        $this->assertEquals('http://localhost/rest/v10/',Helper::configureAPIURL('localhost',10));
        $this->assertEquals('http://localhost/rest/v11/',Helper::configureAPIURL('localhost',11));
        $this->assertEquals('https://localhost/rest/v10/',Helper::configureAPIURL('https://localhost','10'));
        $this->assertEquals('https://localhost/rest/v10/',Helper::configureAPIURL('https://localhost',10));
        $this->assertEquals('https://localhost/rest/v11/',Helper::configureAPIURL('https://localhost',11));
        $this->assertEquals('http://localhost/rest/v10/',Helper::configureAPIURL('localhost/rest/v10','10'));
        $this->assertEquals('http://localhost/rest/v10/',Helper::configureAPIURL('localhost/rest/v10',10));
        $this->assertEquals('http://localhost/rest/v11/',Helper::configureAPIURL('localhost/rest/v10',11));
        $this->assertEquals('http://localhost/rest/v10/',Helper::configureAPIURL('localhost/rest/v10/','10'));
        $this->assertEquals('http://localhost/rest/v10/',Helper::configureAPIURL('localhost/rest/v10/',10));
        $this->assertEquals('http://localhost/rest/v11/',Helper::configureAPIURL('localhost/rest/v10/',11));
        $this->assertEquals('http://localhost/rest/v10/',Helper::configureAPIURL('http://localhost/rest/v10/','10'));
        $this->assertEquals('http://localhost/rest/v10/',Helper::configureAPIURL('http://localhost/rest/v10/',10));
        $this->assertEquals('http://localhost/rest/v11/',Helper::configureAPIURL('http://localhost/rest/v10/',11));
        $this->assertEquals('http://localhost/rest/v10/',Helper::configureAPIURL('http://localhost/','10'));
        $this->assertEquals('http://localhost/rest/v10/',Helper::configureAPIURL('http://localhost/',10));
        $this->assertEquals('http://localhost/rest/v11/',Helper::configureAPIURL('http://localhost/',11));
        $this->assertEquals('https://localhost/rest/v10/',Helper::configureAPIURL('https://localhost/rest/v10','10'));
        $this->assertEquals('https://localhost/rest/v10/',Helper::configureAPIURL('https://localhost/rest/v10',10));
        $this->assertEquals('https://localhost/rest/v11/',Helper::configureAPIURL('https://localhost/rest/v10',11));
        $this->assertEquals('https://localhost/rest/v10/',Helper::configureAPIURL('https://localhost/rest/v10/','10'));
        $this->assertEquals('https://localhost/rest/v10/',Helper::configureAPIURL('https://localhost/rest/v10/',10));
        $this->assertEquals('https://localhost/rest/v11/',Helper::configureAPIURL('https://localhost/rest/v10/',11));
        $this->assertEquals('http://localhost/rest/v10/',Helper::configureAPIURL('http://localhost/rest/v10/rest/v10/rest/v10','10'));
        $this->assertEquals('http://localhost/rest/v10/',Helper::configureAPIURL('http://localhost/rest/v10/rest/v10/rest/v10',10));
        $this->assertEquals('http://localhost/rest/v11/',Helper::configureAPIURL('http://localhost/rest/v10/rest/v10/rest/v10',11));
        $this->assertEquals('https://localhost/SugarTest/rest/v11/',Helper::configureAPIURL('https://localhost/SugarTest/rest/v10/',11));
        $this->assertEquals('http://localhost/Sugar/Test/rest/v10/',Helper::configureAPIURL('http://localhost/Sugar/Test/rest/v10/rest/v10/rest/v10','10'));
        $this->assertEquals('http://localhost/SugarTest/rest/v10/',Helper::configureAPIURL('http://localhost/SugarTest/rest/v10/rest/v10/rest/v10',10));
        $this->assertEquals('http://localhost/SugarTest/rest/v11/',Helper::configureAPIURL('http://localhost/SugarTest/rest/v10/rest/v10/rest/v10',11));
    }

}