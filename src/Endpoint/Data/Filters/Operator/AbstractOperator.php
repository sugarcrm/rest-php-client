<?php
/**
 * ©[2016] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace Sugarcrm\REST\Endpoint\Data\Filters\Operator;

use Sugarcrm\REST\Endpoint\Data\Filters\FilterInterface;

/**
 * Class AbstractOperator
 * @package Sugarcrm\REST\Endpoint\Data\Filters\Operator
 */
abstract class AbstractOperator implements FilterInterface
{
    protected static $_OPERATOR = '';

    protected $field = '';

    protected $data = array();

    public function __construct(array $arguments = array())
    {
        if (!empty($arguments)){
            if (isset($arguments[0])){
                $this->setField($arguments[0]);
            }
            if (isset($arguments[1])){
                $this->setValue($arguments[1]);
            } else {
                $this->setValue(NULL);
            }
        }
    }

    /**
     * @param $field
     */
    public function setField($field)
    {
        $this->field = $field;
    }

    /**
     * @param $value
     */
    public function setValue($value){
        $this->data[static::$_OPERATOR] = $value;
    }

    /**
     * @inheritdoc
     */
    public function compile(){
        return array(
            $this->field => $this->data
        );
    }
}