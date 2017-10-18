<?php
/**
 * ©[2017] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace Sugarcrm\REST\Endpoint\Abstracts;

use MRussell\Http\Request\JSON;
use MRussell\Http\Request\RequestInterface;
use MRussell\Http\Response\ResponseInterface;
use MRussell\REST\Endpoint\Data\EndpointData;
use MRussell\REST\Endpoint\JSON\ModelEndpoint;
use Sugarcrm\REST\Endpoint\Data\FilterData;
use Sugarcrm\REST\Endpoint\SugarEndpointInterface;

/**
 * SugarBean Endpoint acts as a base for any given Module API
 * - Provides action based interface for accessing stock and custom actions
 * @package Sugarcrm\REST\Endpoint\Abstracts
 * @method $this    filterLink(string $link_name = '',string $count = '')
 * @method $this    massLink(string $link_name)
 * @method $this    createLink(string $link_name)
 * @method $this    unlink(string $link_name,string $record_id)
 * @method $this    favorite()
 * @method $this    unfavorite()
 * @method $this    subscribe()
 * @method $this    unsubscribe()
 * @method $this    audit()
 * @method $this    file()
 * @method $this    downloadFile(string $field)
 */
abstract class AbstractSugarBeanEndpoint extends ModelEndpoint implements SugarEndpointInterface
{
    const MODEL_ACTION_VAR = 'action';

    const BEAN_ACTION_RELATE = 'link';

    const BEAN_ACTION_FILTER_RELATED = 'filterLink';

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

    const BEAN_ACTION_TEMP_FILE_UPLOAD = 'tempFile';

    const BEAN_ACTION_ARG1_VAR = 'actionArg1';

    const BEAN_ACTION_ARG2_VAR = 'actionArg2';

    const BEAN_ACTION_ARG3_VAR = 'actionArg3';

    const BEAN_MODULE_VAR = 'module';

    /**
     * @inheritdoc
     */
    protected static $_DEFAULT_PROPERTIES = array(
        self::PROPERTY_AUTH => TRUE,
        self::PROPERTY_DATA => array(
            EndpointData::DATA_PROPERTY_REQUIRED => array(),
            EndpointData::DATA_PROPERTY_DEFAULTS => array()
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
    protected static $_DEFAULT_SUGAR_BEAN_ACTIONS = array(
        self::BEAN_ACTION_FAVORITE => JSON::HTTP_PUT,
        self::BEAN_ACTION_UNFAVORITE => JSON::HTTP_PUT,
        self::BEAN_ACTION_FILTER_RELATED => JSON::HTTP_GET,
        self::BEAN_ACTION_RELATE => JSON::HTTP_POST,
        self::BEAN_ACTION_MASS_RELATE => JSON::HTTP_POST,
        self::BEAN_ACTION_UNLINK => JSON::HTTP_DELETE,
        self::BEAN_ACTION_CREATE_RELATED => JSON::HTTP_POST,
        self::BEAN_ACTION_FOLLOW => JSON::HTTP_POST,
        self::BEAN_ACTION_UNFOLLOW => JSON::HTTP_DELETE,
        self::BEAN_ACTION_AUDIT => JSON::HTTP_GET,
        self::BEAN_ACTION_FILE => JSON::HTTP_GET,
        self::BEAN_ACTION_DOWNLOAD_FILE => JSON::HTTP_GET,
        self::BEAN_ACTION_ATTACH_FILE => JSON::HTTP_POST,
        self::BEAN_ACTION_TEMP_FILE_UPLOAD => JSON::HTTP_POST,
    );

    /**
     * Current Module
     * @var string
     */
    protected $module;

    /**
     * Whether or not a file upload is occurring
     * @var bool
     */
    private $upload = FALSE;

    /**
     * Files waiting to be attached to record
     * @var array
     */
    private $_files = array();

    public function __construct(array $options = array(), array $properties = array())
    {
        parent::__construct($options, $properties);
        foreach(static::$_DEFAULT_SUGAR_BEAN_ACTIONS as $action => $method){
            $this->actions[$action] = $method;
        }
    }

    /**
     * @inheritdoc
     */
    public function compileRequest(){
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
            $options[self::BEAN_MODULE_VAR] = $this->module;
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
     * Configure Uploads on Request
     * @inheritdoc
     * @codeCoverageIgnore
     */
    protected function configureRequest(RequestInterface $Request)
    {
        $Request = parent::configureRequest($Request);
        return $this->configureUploads($Request);
    }

    /**
     * Configure the Uploads Data on the Request Object
     * @param RequestInterface $Request
     * @return RequestInterface
     */
    protected function configureUploads(RequestInterface $Request)
    {
        $Request->setUpload($this->upload);
        if (!empty($this->_files)){
            foreach($this->_files as $key => $properties){
                $Request->addFile($key,$properties['path'],$properties['mimeType'],$properties['filename']);
            }
        }
        return $Request;
    }

    /**
     * Add a reset for Upload Settings
     * @inheritdoc
     * @codeCoverageIgnore
     */
    protected function configureResponse(ResponseInterface $Response)
    {
        $this->resetUploads();
        return parent::configureResponse($Response);
    }

    /**
     * Reset the Upload Settings back to defaults
     */
    protected function resetUploads(){
        if ($this->upload){
            $this->getData()->reset();
        }
        $this->upload = FALSE;
        $this->_files = array();
    }

    /**
     * Redefine some Actions to another Action, for use in URL
     * @inheritdoc
     */
    protected function configureURL(array $options) {
        $action = $this->getCurrentAction();
        switch($action){
            case self::BEAN_ACTION_CREATE_RELATED:
            case self::BEAN_ACTION_MASS_RELATE:
            case self::BEAN_ACTION_UNLINK:
            case self::BEAN_ACTION_FILTER_RELATED:
                $action = self::BEAN_ACTION_RELATE;
                break;
            case self::BEAN_ACTION_ATTACH_FILE:
            case self::BEAN_ACTION_DOWNLOAD_FILE:
            case self::BEAN_ACTION_TEMP_FILE_UPLOAD:
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
        $options = $this->getOptions();
        $options[self::BEAN_MODULE_VAR] = $this->module;
        if (isset($options[self::BEAN_ACTION_ARG1_VAR])) unset($options[self::BEAN_ACTION_ARG1_VAR]);
        if (isset($options[self::BEAN_ACTION_ARG2_VAR])) unset($options[self::BEAN_ACTION_ARG2_VAR]);
        if (isset($options[self::BEAN_ACTION_ARG3_VAR])) unset($options[self::BEAN_ACTION_ARG3_VAR]);
        if (!empty($arguments)){
            switch($action){
                case self::BEAN_ACTION_TEMP_FILE_UPLOAD:
                case self::BEAN_ACTION_ATTACH_FILE:
                    $this->upload = TRUE;
                case self::BEAN_ACTION_RELATE:
                case self::BEAN_ACTION_DOWNLOAD_FILE:
                case self::BEAN_ACTION_UNLINK:
                case self::BEAN_ACTION_CREATE_RELATED:
                case self::BEAN_ACTION_FILTER_RELATED:
                    if (isset($arguments[0])){
                        $options[self::BEAN_ACTION_ARG1_VAR] = $arguments[0];
                        if (isset($arguments[1])){
                            $options[self::BEAN_ACTION_ARG2_VAR] = $arguments[1];
                            if (isset($arguments[2])){
                                $options[self::BEAN_ACTION_ARG3_VAR] = $arguments[2];
                            }
                        }
                    }
            }
        }
        $this->setOptions($options);
        parent::configureAction($action,$arguments);
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
                    $this->reset();
                    $this->update($body);
                }
                break;
            case self::BEAN_ACTION_TEMP_FILE_UPLOAD:
                if (is_array($body) && isset($body['record'])){
                    $this->reset();
                    $model = array(
                        'filename_guid' => $body['record']['id'],
                        'filename' => $body['filename']['guid']
                    );
                    $this->update($model);
                }
            default:
                parent::updateModel();
        }
    }

    /**
     * System friendly name for subscribing to a record
     * @return self
     */
    public function follow()
    {
        return $this->subscribe();
    }

    /**
     * System friendly name for unsubscribing to a record
     * @return self
     */
    public function unfollow()
    {
        return $this->unsubscribe();
    }

    /**
     * Human friendly method name for Link action
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
     * Human friendly overload for downloadFile action
     * @param $field - Name of File Field
     * @return self
     */
    public function getFile($field){
        return $this->downloadFile($field);
    }

    /**
     * Human friendly overload for filterLink action
     * @param $linkName - Name of Relationship Link
     * @param bool $count
     * @return self
     */
    public function getRelated($linkName,$count = false){
        if ($count){
            return $this->filterLink($linkName,'count');
        }
        return $this->filterLink($linkName);
    }

    /**
     * Filter generator for Related Links
     * @param $linkName - Name of Relationship Link
     * @param bool $count - Whether or not to just do a count request
     * @return FilterData
     */
    public function filterRelated($linkName,$count = false){
        $Filter = new FilterData($this);
        $this->setCurrentAction(self::BEAN_ACTION_FILTER_RELATED);
        $args = array($linkName);
        if ($count){
            $args[] = 'count';
        }
        $this->configureAction($this->action,$args);
        return $Filter;
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

    /**
     * Overloading attachFile dynamic method to handle more functionality for file uploads
     * @param $fileField
     * @param $filePath
     * @param bool $deleteOnFail
     * @param string $mimeType
     * @param string $uploadName
     * @return $this
     * @throws \MRussell\REST\Exception\Endpoint\InvalidDataType
     */
    public function attachFile($fileField,$filePath,$deleteOnFail = false,$mimeType = '',$uploadName='')
    {
        $this->setCurrentAction(self::BEAN_ACTION_ATTACH_FILE,array($fileField));
        $this->configureFileUploadData($deleteOnFail);
        $this->addFile($fileField,array(
            'path' => $filePath,
            'mimeType' => $mimeType,
            'filename' => $uploadName
        ));
        return $this->execute();
    }

    /**
     * Overloading tempFile dynamic method to provide more functionality
     * @param $fileField
     * @param $filePath
     * @param bool $deleteOnFail
     * @param string $mimeType
     * @param string $uploadName
     * @return $this
     * @throws \MRussell\REST\Exception\Endpoint\InvalidDataType
     */
    public function tempFile($fileField,$filePath,$deleteOnFail = false,$mimeType = '',$uploadName='')
    {
        $model = $this->asArray();
        $idKey = $this->modelIdKey();
        if (isset($model[$idKey])){
            $this->reset();
        }
        $this->set($idKey,'temp');
        $this->setCurrentAction(self::BEAN_ACTION_TEMP_FILE_UPLOAD,array($fileField));
        $this->configureFileUploadData($deleteOnFail);
        $this->addFile($fileField,array(
            'path' => $filePath,
            'mimeType' => $mimeType,
            'filename' => $uploadName
        ));
        return $this->execute();
    }

    /**
     * @param bool $deleteOnFail
     * @throws \MRussell\REST\Exception\Endpoint\InvalidDataType
     */
    protected function configureFileUploadData($deleteOnFail = FALSE){
        $data = array(
            'format' => 'sugar-html-json',
            'delete_if_fails' => $deleteOnFail,
        );
        if ($deleteOnFail){
            $token = $this->getAuth()->getToken();
            $data['oauth_token'] = $token['access_token'];
        }
        $this->setData($data);
    }

    /**
     * Add a file to the internal Files array to be added to the Request
     * @param $name
     * @param array $properties
     * @return $this
     */
    protected function addFile($name,array $properties)
    {
        if (isset($properties['path'])){
            $this->upload = TRUE;
            $this->_files[$name] = $properties;
        }
        return $this;
    }

}