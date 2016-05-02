<?php
/**
 * ©[2016] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace SugarAPI\SDK\Tests\EntryPoint\POST;

use SugarAPI\SDK\EntryPoint\POST\ModuleRecordFileField;

/**
 * Class ModuleRecordFileFieldTest
 * @package SugarAPI\SDK\Tests\EntryPoint\POST
 * @coversDefaultClass SugarAPI\SDK\EntryPoint\POST\ModuleRecordFileField
 * @group entryPoints
 */
class ModuleRecordFileFieldTest extends \PHPUnit_Framework_TestCase {

    public static function setUpBeforeClass()
    {
    }

    public static function tearDownAfterClass()
    {
    }

    protected $url = 'http://localhost/rest/v10/';
    protected $options = array(
        'Notes',
        '1234abc',
        'filename'
    );
    protected $data;

    public function setUp()
    {
        $this->data = __FILE__;
        parent::setUp();
    }

    public function tearDown()
    {
        parent::tearDown();
    }

    /**
     * @covers ::configureData
     * @covers ::setFileFieldValue
     * @group sdkEP
     */
    public function testConfigureData(){
        $EP = new ModuleRecordFileField($this->url,$this->options);
        $EP->execute($this->data);

        $configuredData = array(
            'filename' => '@'.__FILE__,
            'format' => 'sugar-html-json',
            'delete_if_fails' => false
        );
        $this->assertEquals($configuredData,$EP->getData());
        unset($EP);

        $EP = new ModuleRecordFileField($this->url);
        $EP->setOptions($this->options);
        $EP->execute($this->data);
        $this->assertEquals($configuredData,$EP->getData());
        unset($EP);

        $EP = new ModuleRecordFileField($this->url,$this->options);
        $data = array(
            'filename' => $this->data
        );
        $EP->execute($data);
        $this->assertEquals($configuredData,$EP->getData());
        unset($EP);

        $EP = new ModuleRecordFileField($this->url,$this->options);
        $data = array(
            'filename' => '@'.$this->data,
            'delete_if_fails' => true
        );
        $EP->execute($data);
        $configuredData['delete_if_fails'] = true;
        $this->assertEquals($configuredData,$EP->getData());
        unset($EP);

        $EP = new ModuleRecordFileField($this->url,$this->options);
        $EP->execute($configuredData);
        $this->assertEquals($configuredData,$EP->getData());
        unset($EP);

        $EP = new ModuleRecordFileField($this->url);
        $EP->setUrl($this->url."Notes/1234a/file/filename");
        $EP->execute($configuredData);
        $this->assertEquals($configuredData,$EP->getData());
        unset($EP);
    }

    /**
     * @covers ::configureData
     * @expectedException SugarAPI\SDK\Exception\EntryPoint\RequiredOptionsException
     * @expectedExceptionMessageRegExp /Options are required, when passing String for data/
     * @group sdkEP
     */
    public function testRequiredOptionException(){
        $EP = new ModuleRecordFileField($this->url);
        $EP->setUrl($this->url."Notes/1234a/file/filename");
        $EP->execute($this->data);
    }


}
