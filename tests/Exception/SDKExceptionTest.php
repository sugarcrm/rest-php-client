<?php
/**
 * ©[2016] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace SugarAPI\SDK\Tests\Exception;

use SugarAPI\SDK\Exception\SDKException;

/**
 * Class SDKExceptionTest
 * @package SugarAPI\SDK\Tests\Exception
 * @coversDefaultClass SugarAPI\SDK\Exception\SDKException
 * @group exceptions
 */
class SDKExceptionTest extends \PHPUnit_Framework_TestCase {

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
     * @covers ::__construct
     * @group entryPointException
     * @expectedException SugarAPI\SDK\Exception\SDKException
     */
    public function testJson(){
        throw new SDKException();
    }
}
