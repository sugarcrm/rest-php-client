<?php
/**
 * ©[2016] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace SugarAPI\SDK\Tests\Endpoint\POST;

use SugarAPI\SDK\Endpoint\POST\ModuleRecordFileField;

/**
 * Class ModuleRecordFileFieldTest
 * @package SugarAPI\SDK\Tests\Endpoint\POST
 * @coversDefaultClass SugarAPI\SDK\Endpoint\POST\ModuleRecordFileField
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
    protected $data = '';

    public function setUp()
    {
        $this->data = realpath(__FILE__);
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

        if (version_compare(PHP_VERSION, '5.5.0') >= 0){
            $configuredData = array(
                'filename' => new \CURLFile(__FILE__),
                'format' => 'sugar-html-json',
                'delete_if_fails' => false
            );
        } else {
            $configuredData = array(
                'filename' => '@'.__FILE__,
                'format' => 'sugar-html-json',
                'delete_if_fails' => false
            );
        }

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
     * @expectedException SugarAPI\SDK\Exception\Endpoint\RequiredOptionsException
     * @expectedExceptionMessageRegExp /Options are required, when passing String for data/
     * @group sdkEP
     */
    public function testRequiredOptionException(){
        $EP = new ModuleRecordFileField($this->url);
        $EP->setUrl($this->url."Notes/1234a/file/filename");
        $EP->execute($this->data);
    }


}
