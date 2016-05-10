<?php
/**
 * ©[2016] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace SugarAPI\SDK\Tests\Request;
use SugarAPI\SDK\Request\POST;

/**
 * Class POSTTest
 * @package SugarAPI\SDK\Tests\Request\POSTTest
 * @coversDefaultClass SugarAPI\SDK\Request\POST
 * @group requests
 */
class POSTTest extends \PHPUnit_Framework_TestCase {

    public static function setUpBeforeClass()
    {
    }

    public static function tearDownAfterClass()
    {
    }
    protected $body = array(
        'foo' => 'bar'
    );

    public function setUp()
    {
        parent::setUp();
    }

    public function tearDown()
    {
        parent::tearDown();
    }

    /**
     * @covers ::setBody
     * @group requests
     */
    public function testSetBody(){
        $Request = new POST();
        $Request->setBody(array());
        $this->assertEquals('[]',$Request->getBody());
        $this->assertEquals('[]',$Request->getOptions()[CURLOPT_POSTFIELDS]);
        $Request->setBody(array('test'));
        $this->assertEquals('["test"]',$Request->getBody());
        $this->assertEquals('["test"]',$Request->getOptions()[CURLOPT_POSTFIELDS]);
        $Request->setBody('test');
        $this->assertEquals('"test"',$Request->getBody());
        $this->assertEquals('"test"',$Request->getOptions()[CURLOPT_POSTFIELDS]);
        $Request->setBody(1234);
        $this->assertEquals(1234,$Request->getBody());
        $this->assertEquals(1234,$Request->getOptions()[CURLOPT_POSTFIELDS]);
        $Request->setBody($this->body);
        $this->assertEquals(json_encode($this->body),$Request->getBody());
        $this->assertEquals(json_encode($this->body),$Request->getOptions()[CURLOPT_POSTFIELDS]);
    }

}
