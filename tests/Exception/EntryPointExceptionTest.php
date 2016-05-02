<?php
/**
 * ©[2016] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace SugarAPI\SDK\Tests\Exception;

use SugarAPI\SDK\Exception\EntryPoint\EntryPointException;

/**
 * Class EntryPointExceptionTest
 * @package SugarAPI\SDK\Tests\Exception
 * @coversDefaultClass SugarAPI\SDK\Exception\EntryPoint\EntryPointException
 * @group exceptions
 */
class EntryPointExceptionTest extends \PHPUnit_Framework_TestCase {

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
     * @expectedException SugarAPI\SDK\Exception\EntryPoint\EntryPointException
     */
    public function testJson(){
        throw new EntryPointException(get_called_class(),"Test");
    }
}
