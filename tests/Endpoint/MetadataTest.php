<?php
/**
 * ©[2017] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace Sugarcrm\REST\Tests\Endpoint;

use Sugarcrm\REST\Auth\SugarOAuthController;
use Sugarcrm\REST\Endpoint\Metadata;


/**
 * Class MetadataTest
 * @package Sugarcrm\REST\Tests\Endpoint
 * @coversDefaultClass Sugarcrm\REST\Endpoint\Metadata
 * @group MetadataTest
 */
class MetadataTest extends \PHPUnit_Framework_TestCase
{

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
     * @covers ::getHash
     * @covers ::getPublic
     */
    public function testGetMetadataTypes(){
        $Metadata = new Metadata();
        $Metadata->setAuth(new SugarOAuthController());
        $Metadata->setBaseUrl('http://localhost/rest/v10');
        $Metadata->getHash();
        $request = $Metadata->getRequest();
        $this->assertEquals(array($Metadata::METADATA_TYPE_HASH),$Metadata->getOptions());
        $this->assertEquals('http://localhost/rest/v10/metadata/_hash',$request->getURL());

        $Metadata->getPublic();
        $request = $Metadata->getRequest();
        $this->assertEquals(array($Metadata::METADATA_TYPE_PUBLIC),$Metadata->getOptions());
        $this->assertEquals('http://localhost/rest/v10/metadata/public',$request->getURL());
    }

}
