<?php
/**
 * ©[2016] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace SugarAPI\SDK\Endpoint\POST;

use SugarAPI\SDK\Endpoint\Abstracts\POST\AbstractPostFileEndpoint;
use SugarAPI\SDK\Exception\Endpoint\RequiredOptionsException;

class ModuleRecordFileField extends AbstractPostFileEndpoint
{
    /**
     * @inheritdoc
     */
    protected $_URL = '$module/$record/file/$field';

    /**
     * @inheritdoc
     */
    protected $_DATA_TYPE = 'array';

    /**
     * @inheritdoc
     */
    protected $_REQUIRED_DATA = array(
        'format' => 'sugar-html-json',
        'delete_if_fails' => false
    );

    /**
     * Allow for shorthand calling of attachFile method.
     * Users can simply submit the File in via string, or pass the filename => path
     * @param $data
     * @throws RequiredOptionsException
     */
    protected function configureData($data)
    {
        if (is_string($data)) {
            if (!empty($this->Options)) {
                $fileField = end($this->Options);
                $data = array(
                    $fileField => $data
                );
            } else {
                throw new RequiredOptionsException(get_called_class(), "Options are required, when passing String for data.");
            }
        }
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                if (!array_key_exists($key, $this->_REQUIRED_DATA)) {
                    $data[$key] = $this->setFileFieldValue($value);
                }
            }
        }
        parent::configureData($data);
    }

    /**
     * Configure the filepath to have an @ symbol in front if one is not found
     * @param string $value
     * @return string
     */
    protected function setFileFieldValue($value)
    {
        if (version_compare(PHP_VERSION, '5.5.0') >= 0){
            if (!($value instanceof \CURLFile)){
                $value = ltrim($value,"@");
                $value = new \CURLFile($value);
            }
        } else {
            if (strpos($value, '@') !== 0) {
                $value = '@'.$value;
            }
        }
        return $value;
    }
}
