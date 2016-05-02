<?php
/**
 * ©[2016] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace SugarAPI\SDK\EntryPoint\POST;

use SugarAPI\SDK\EntryPoint\Abstracts\POST\AbstractPostFileEntryPoint;
use SugarAPI\SDK\Exception\EntryPoint\RequiredOptionsException;

class ModuleRecordFileField extends AbstractPostFileEntryPoint {

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
        'delete_if_fails' => FALSE
    );

    /**
     * Allow for shorthand calling of attachFile method.
     * Users can simply submit the File in via string, or pass the filename => path
     * @param $data
     * @throws RequiredOptionsException
     */
    protected function configureData($data){
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
        if (is_array($data)){
            foreach ($data as $key => $value){
                if (!array_key_exists($key,$this->_REQUIRED_DATA)){
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
    protected function setFileFieldValue($value){
        if (strpos($value, '@')===FALSE){
            $value = '@'.$value;
        }
        return $value;
    }

}