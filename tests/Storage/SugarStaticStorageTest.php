<?php
/**
 * ©[2017] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace Sugarcrm\REST\Tests\Storage;

use Sugarcrm\REST\Storage\SugarStaticStorage;


/**
 * Class SugarStaticStorageTest
 * @package Sugarcrm\REST\Tests\Storage
 * @coversDefaultClass Sugarcrm\REST\Storage\SugarStaticStorage
 * @group SugarStaticStorageTest
 */
class SugarStaticStorageTest extends \PHPUnit_Framework_TestCase
{

    protected $token = array(
        'access_token' => '1234'
    );

    public static function setUpBeforeClass()
    {
        //Add Setup for static properties here
    }

    public static function tearDownAfterClass()
    {
        //Add Tear Down for static properties here
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
     * @covers ::store
     * @covers ::get
     * @covers ::remove
     * @covers ::formatKey
     *
     */
    public function testStorage(){
        $Storage = new SugarStaticStorage();
        $this->assertEquals(true,$Storage->store(array(
            'server' => 'test.sugarondemand.com'
        ),$this->token));
        $token = $Storage->get('test.sugarondemand.com');
        $this->assertEquals($this->token,$token);
        $this->assertEquals(true,$Storage->remove(array(
            'server' => 'test.sugarondemand.com'
        )));
        $token = $Storage->get('test.sugarondemand.com');
        $this->assertEquals(NULL,$token);


        $this->assertEquals(true,$Storage->store(array(
            'client_id' => 'test',
            'platform' => 'base'
        ),$this->token));
        $token = $Storage->get('test_base');
        $this->assertEquals($this->token,$token);
        $this->assertEquals(true,$Storage->store(array(
            'server' => 'test.sugarondemand.com',
            'platform' => 'base'
        ),$this->token));
        $token = $Storage->get('test.sugarondemand.com_base');
        $this->assertEquals($this->token,$token);
        $this->assertEquals(true,$Storage->store('foobar',$this->token));
        $token = $Storage->get('foobar');
        $this->assertEquals($this->token,$token);

        $this->assertEquals(true,$Storage->remove(array(
            'server' => 'test.sugarondemand.com',
            'platform' => 'base'
        )));
        $this->assertEquals(true,$Storage->remove('test_base'));
        $token = $Storage->get(array(
            'server' => 'test.sugarondemand.com',
            'platform' => 'base'
        ));
        $this->assertEquals(NULL,$token);

        $this->assertEquals(true,$Storage->store(array(
            'server' => 'test.sugarondemand.com',
            'platform' => 'base',
            'client_id' => 'foo',
            'sudo' => 'max'
        ),$this->token));
        $token = $Storage->get('test.sugarondemand.com_foo_base_sudomax');
        $this->assertEquals($this->token,$token);
    }

}
