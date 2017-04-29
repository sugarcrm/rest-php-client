<?php
/**
 * ©[2016] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace Sugarcrm\REST\Endpoint\Abstracts;

use MRussell\Http\Request\JSON;
use MRussell\REST\Endpoint\JSON\ModelEndpoint;
use Sugarcrm\REST\Endpoint\SugarEndpointInterface;

abstract class AbstractSugarBeanEndpoint extends ModelEndpoint implements SugarEndpointInterface
{
    const MODEL_ACTION_VAR = 'action';

    const BEAN_ACTION_RELATE = 'link';

    const BEAN_ACTION_MASS_RELATE = 'massLink';

    const BEAN_ACTION_CREATE_RELATED = 'createLink';

    const BEAN_ACTION_UNLINK = 'unlink';

    const BEAN_ACTION_FAVORITE = 'favorite';

    const BEAN_ACTION_UNFAVORITE = 'unfavorite';

    const BEAN_ACTION_FOLLOW = 'subscribe';

    const BEAN_ACTION_UNFOLLOW = 'unsubscribe';

    const BEAN_ACTION_AUDIT = 'audit';

    const BEAN_ACTION_FILE = 'file';

    const BEAN_ACTION_DOWNLOAD_FILE = 'downloadFile';

    const BEAN_ACTION_ATTACH_FILE = 'attachFile';

    /**
     * @inheritdoc
     */
    protected static $_DEFAULT_PROPERTIES = array(
        'auth' => TRUE,
        'data' => array(
            'required' => array(),
            'defaults' => array()
        )
    );

    /**
     * @inheritdoc
     */
    protected static $_ENDPOINT_URL = '$module/$id/$:action/$:actionArg1/$:actionArg2/$:actionArg3';

    /**
     * All the extra actions that can be done on a Sugar Bean
     * @var array
     */
    protected $actions = array(
        self::BEAN_ACTION_FAVORITE => JSON::HTTP_PUT,
        self::BEAN_ACTION_UNFAVORITE => JSON::HTTP_PUT,
        self::BEAN_ACTION_RELATE => JSON::HTTP_POST,
        self::BEAN_ACTION_MASS_RELATE => JSON::HTTP_POST,
        self::BEAN_ACTION_UNLINK => JSON::HTTP_DELETE,
        self::BEAN_ACTION_CREATE_RELATED => JSON::HTTP_POST,
        self::BEAN_ACTION_FOLLOW => JSON::HTTP_POST,
        self::BEAN_ACTION_UNFOLLOW => JSON::HTTP_PUT,
        self::BEAN_ACTION_AUDIT => JSON::HTTP_GET,
        self::BEAN_ACTION_FILE => JSON::HTTP_GET,
        self::BEAN_ACTION_DOWNLOAD_FILE => JSON::HTTP_GET,
        self::BEAN_ACTION_ATTACH_FILE => JSON::HTTP_POST
    );

    /**
     * Current Module
     * @var string
     */
    protected $module;

    /**
     * @inheritdoc
     */
    public function compileRequest(){
        $this->configureAction($this->getCurrentAction());
        return $this->configureRequest($this->getRequest());
    }

    /**
     * Passed in options get changed such that 1st Option (key 0) becomes Module
     * 2nd Option (Key 1) becomes ID
     * @inheritdoc
     */
    public function setOptions(array $options) {
        if (isset($options[0])){
            $this->setModule($options[0]);
            $options['module'] = $this->module;
            unset($options[0]);
        }
        if (isset($options[1])){
            $this->set(static::$_MODEL_ID_KEY,$options[1]);
            $options[self::MODEL_ID_VAR] = $options[1];
            unset($options[1]);
        }
        return parent::setOptions($options);
    }

    /**
     * Set the Sugar Module currently being used
     * @param $module
     * @return $this
     */
    public function setModule($module){
        $this->module = $module;
        return $this;
    }

    /**
     * Get the Sugar Module currently configured
     * @return mixed
     */
    public function getModule(){
        return $this->module;
    }

    /**
     * Redefine some Actions to another Action, for use in URL
     * @inheritdoc
     */
    protected function configureURL(array $options) {
        $action = $this->action;
        switch($this->action){
            case self::BEAN_ACTION_CREATE_RELATED:
            case self::BEAN_ACTION_MASS_RELATE:
            case self::BEAN_ACTION_UNLINK:
                $action = self::BEAN_ACTION_RELATE;
                break;
            case self::BEAN_ACTION_DOWNLOAD_FILE:
            case self::BEAN_ACTION_ATTACH_FILE:
                $action = self::BEAN_ACTION_FILE;
                break;
            case self::MODEL_ACTION_DELETE:
            case self::MODEL_ACTION_UPDATE:
            case self::MODEL_ACTION_CREATE:
            case self::MODEL_ACTION_RETRIEVE:
                $action = NULL;
                break;
        }
        if ($action !== NULL){
            $options[self::MODEL_ACTION_VAR] = $action;
        } else {
            if (isset($options[self::MODEL_ACTION_VAR])){
                unset($options[self::MODEL_ACTION_VAR]);
            }
        }
        return parent::configureURL($options);
    }

    /**
     * @inheritdoc
     */
    protected function configureAction($action,array $arguments = array()) {
        if (!empty($arguments)){
            switch($action){
                case self::BEAN_ACTION_RELATE:
                case self::BEAN_ACTION_ATTACH_FILE:
                case self::BEAN_ACTION_DOWNLOAD_FILE:
                case self::BEAN_ACTION_UNLINK:
                case self::BEAN_ACTION_CREATE_RELATED:
                    if (isset($arguments[0])){
                        $this->options['actionArg1'] = $arguments[0];
                    }
                    if (isset($arguments[1])){
                        $this->options['actionArg2'] = $arguments[1];
                    }
                    if (isset($arguments[2])){
                        $this->options['actionArg3'] = $arguments[2];
                    }
            }
        }
        $this->options['module'] = $this->module;
        parent::configureAction($action);
    }

    /**
     * @inheritdoc
     */
    protected function updateModel()
    {
        $body = $this->Response->getBody();
        switch ($this->action){
            case self::BEAN_ACTION_FAVORITE:
            case self::BEAN_ACTION_UNFAVORITE:
                if (is_array($body)){
                    $this->update($body);
                }
                break;
            default:
                parent::updateModel();
        }
    }

    /**
     * Human friendly method name for Link, simply forwards to Link method
     * @param string $linkName - Relationship Link Name
     * @param string $related_id - ID to Relate
     * @return self
     */
    public function relate($linkName,$related_id){
        return $this->link($linkName,$related_id);
    }

    /**
     * Another Human Friendly overload, file & files are the same action
     * @return self
     */
    public function files(){
        return $this->file();
    }

    /**
     * Human friendly overload for downloadFile method
     * @param $field - Name of File Field
     * @return self
     */
    public function getFile($field){
        return $this->downloadFile($field);
    }

    /**
     * Mass Related records to current Bean Model
     * @param $linkName
     * @param array $related_ids
     */
    public function massRelate($linkName,array $related_ids){
        $this->setData(array(
            'link_name' => $linkName,
            'ids' => $related_ids
        ));
        return $this->massLink();
    }


}