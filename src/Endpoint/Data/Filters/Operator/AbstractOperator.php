<?php

namespace Sugarcrm\REST\Endpoint\Data\Filters\Operator;

use Sugarcrm\REST\Endpoint\Data\Filters\FilterInterface;

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

    public function setField($field)
    {
        $this->field = $field;
    }

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