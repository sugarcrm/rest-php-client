<?php
/**
 * ©[2016] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace SugarAPI\SDK\Tests\Exception;

use SugarAPI\SDK\Exception\Authentication\AuthenticationException;

/**
 * Class AuthenticationExceptionTest
 * @package SugarAPI\SDK\Tests\Exception
 * @coversDefaultClass SugarAPI\SDK\Exception\Authentication\AuthenticationException
 * @group exceptions
 */
class AuthenticationExceptionTest extends \PHPUnit_Framework_TestCase {

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
     * @expectedException SugarAPI\SDK\Exception\Authentication\AuthenticationException
     */
    public function testJson(){
        throw new AuthenticationException("Test");
    }
}
