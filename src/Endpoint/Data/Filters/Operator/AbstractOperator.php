<?php
/**
 * ©[2022] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace Sugarcrm\REST\Endpoint\Data\Filters\Operator;

use Sugarcrm\REST\Endpoint\Data\Filters\FilterInterface;

/**
 * Class AbstractOperator
 * @package Sugarcrm\REST\Endpoint\Data\Filters\Operator
 */
abstract class AbstractOperator implements FilterInterface
{
    /**
     * The Sugar Operator representation
     * @var string
     */
    protected static $_OPERATOR = '';

    /**
     * The field the Operator applies to
     * @var string
     */
    protected $field;

    /**
     * The value being the Operator compares to
     * @var mixed
     */
    protected $value;


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
     * Set the field on the Operator
     * @param $field string
     * @return $this
     */
    public function setField($field)
    {
        $this->field = (string) $field;
        return $this;
    }

    /**
     * Get the field configured on the Operator
     * @return string
     */
    public function getField(){
        return $this->field;
    }

    /**
     * Set the Value on the Operator
     * @param $value
     * @return $this
     */
    public function setValue($value){
        $this->value = $value;
        return $this;
    }

    /**
     * Get the value configure on the Operator
     * @return mixed
     */
    public function getValue(){
        return $this->value;
    }

    /**
     * @inheritdoc
     */
    public function compile(){
        return array(
            $this->getField() => array(
                static::$_OPERATOR => $this->getValue()
            )
        );
    }
}