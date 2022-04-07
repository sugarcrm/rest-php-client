<?php

/**
 * ©[2022] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace Sugarcrm\REST\Endpoint\Abstracts;


use GuzzleHttp\Psr7\MultipartStream;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Utils;
use MRussell\REST\Endpoint\Data\EndpointData;
use MRussell\REST\Endpoint\Interfaces\EndpointInterface;
use MRussell\REST\Endpoint\ModelEndpoint;
use MRussell\REST\Traits\PsrLoggerTrait;
use Sugarcrm\REST\Endpoint\Data\FilterData;
use Sugarcrm\REST\Endpoint\SugarEndpointInterface;
use Sugarcrm\REST\Endpoint\Traits\CompileRequestTrait;
use Sugarcrm\REST\Endpoint\Traits\FieldsDataTrait;
use Sugarcrm\REST\Endpoint\Traits\ModuleAwareTrait;

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
 * @method $this    duplicateCheck()
 */
abstract class AbstractSugarBeanEndpoint extends ModelEndpoint implements SugarEndpointInterface {
    use CompileRequestTrait,
        PsrLoggerTrait,
        ModuleAwareTrait,
        FieldsDataTrait;

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
    const BEAN_ACTION_DUPLICATE_CHECK = 'duplicateCheck';
    const BEAN_ACTION_ARG1_VAR = 'actionArg1';
    const BEAN_ACTION_ARG2_VAR = 'actionArg2';
    const BEAN_ACTION_ARG3_VAR = 'actionArg3';

    const BEAN_MODULE_URL_ARG = 'module';

    /**
     * @inheritdoc
     */
    protected static $_DEFAULT_PROPERTIES = array(
        self::PROPERTY_AUTH => true,
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
        self::BEAN_ACTION_FAVORITE => "PUT",
        self::BEAN_ACTION_UNFAVORITE => "PUT",
        self::BEAN_ACTION_FILTER_RELATED => "GET",
        self::BEAN_ACTION_RELATE => "POST",
        self::BEAN_ACTION_MASS_RELATE => "POST",
        self::BEAN_ACTION_UNLINK => "DELETE",
        self::BEAN_ACTION_CREATE_RELATED => "POST",
        self::BEAN_ACTION_FOLLOW => "POST",
        self::BEAN_ACTION_UNFOLLOW => "DELETE",
        self::BEAN_ACTION_AUDIT => "GET",
        self::BEAN_ACTION_FILE => "GET",
        self::BEAN_ACTION_DOWNLOAD_FILE => "GET",
        self::BEAN_ACTION_ATTACH_FILE => "POST",
        self::BEAN_ACTION_TEMP_FILE_UPLOAD => "POST",
        self::BEAN_ACTION_DUPLICATE_CHECK => "POST"
    );

    /**
     * Current Module
     * @var string
     */
    protected $_beanName = "";

    /**
     * Whether or not a file upload is occurring
     * @var bool
     */
    protected $_upload = false;

    /**
     * Files waiting to be attached to record
     * @var array
     */
    protected $_file = [];

    /**
     * @var bool
     */
    protected $_deleteFileOnFail = false;

    public function __construct(array $urlArgs = array(), array $properties = array()) {
        parent::__construct($urlArgs, $properties);
        foreach (static::$_DEFAULT_SUGAR_BEAN_ACTIONS as $action => $method) {
            $this->actions[$action] = $method;
        }
    }

    /**
     * Passed in options get changed such that 1st Option (key 0) becomes Module
     * 2nd Option (Key 1) becomes ID
     * @inheritdoc
     */
    public function setUrlArgs(array $args): EndpointInterface {
        $args = $this->configureModuleUrlArg($args);
        if (isset($args[1])) {
            $this->set($this->modelIdKey(), $args[1]);
            unset($args[1]);
        }
        return parent::setUrlArgs($args);
    }

    /**
     * Configure Uploads on Request
     * @inheritdoc
     */
    protected function configureRequest(Request $request,$data): Request
    {
        if ($this->_upload && !empty($this->_file['field']) && $this->_file['path']){
            $request = $this->configureFileUploadRequest($request);
            $data = null;
        } else {
            if ($this->getCurrentAction() == self::MODEL_ACTION_RETRIEVE){
                $data = $this->configureFieldsDataProps($data);
            }
        }
        return parent::configureRequest($request,$data);
    }

    /**
     * Configure File Upload Request using Multipart Data
     * @param Request $request
     * @return Request
     */
    protected function configureFileUploadRequest(Request $request): Request {
        $uri = $request->getUri();
        $data = $this->configureFileUploadData();
        $request = $request->withUri($uri->withQuery(\http_build_query($data)));
        $multiPartOptions = [];
        if (file_exists($this->_file['path'])){
            $fileProps = [
                'name' => $this->_file['field'],
                'contents' => Utils::streamFor(fopen($this->_file['path'],'r',true)),
            ];
            if (isset($this->_file['filename'])){
                $fileProps['filename'] = $this->_file['filename'];
            }
            $multiPartOptions[] = $fileProps;
        }
        $data = new MultipartStream($multiPartOptions);
        $request = $request->withBody($data);
        return $request->withHeader('Content-Type','multipart/form-data; boundary=' . $data->getBoundary());
    }

    /**
     * @return array|\GuzzleHttp\Psr7\Stream|\MRussell\REST\Endpoint\Data\DataInterface|string|null
     */
    protected function configurePayload()
    {
        $data = $this->getData();
        switch ($this->getCurrentAction()) {
            case self::MODEL_ACTION_CREATE:
            case self::MODEL_ACTION_UPDATE:
                $data->reset();
                break;
            case self::BEAN_ACTION_DUPLICATE_CHECK:
                $data->reset();
                $data->set($this->toArray());
                break;
        }
        return parent::configurePayload(); // TODO: Change the autogenerated stub
    }

    /**
     * @inheritdoc
     *  - Add a reset for Upload Settings
     *  - Sync Model on Favorite/Unfavorite actions
     * - Set filename_guid and filename for temp file uploads
     */
    public function parseResponse(Response $response): void {
        $this->resetUploads();
        if ($response->getStatusCode() == 200) {
            switch ($this->getCurrentAction()) {
                case self::BEAN_ACTION_TEMP_FILE_UPLOAD:
                    $body = $this->getResponseBody();
                    if (isset($body['record'])) {
                        $this->set(array(
                            'filename_guid' => $body['record']['id'],
                            'filename' => $body['filename']['guid']
                        ));
                    }
                    return;
                case self::BEAN_ACTION_FAVORITE:
                case self::BEAN_ACTION_UNFAVORITE:
                    $body = $this->getResponseBody();
                    $this->clear();
                    $this->syncFromApi($this->parseResponseBodyToArray($body,$this->getModelResponseProp()));
                    return;
            }
        }
        parent::parseResponse($response);
    }

    /**
     * @inheritDoc
     */
    public function reset()
    {
        $this->_fields = [];
        $this->_view = '';
        return parent::reset();
    }

    /**
     * @inheritDoc
     */
    public function clear()
    {
        $this->resetUploads();
        return parent::clear();
    }

    /**
     * Reset the Upload Settings back to defaults
     */
    protected function resetUploads() {
        if ($this->_upload) {
            $this->getData()->reset();
        }
        $this->_upload = false;
        $this->_file = [];
        $this->_deleteFileOnFail = false;
    }

    /**
     * Redefine some Actions to another Action, for use in URL
     * @inheritdoc
     */
    protected function configureURL(array $urlArgs): string {
        $action = null;
        $urlArgs = $this->configureModuleUrlArg($urlArgs);
        switch ($this->getCurrentAction()) {
            case self::BEAN_ACTION_CREATE_RELATED:
            case self::BEAN_ACTION_MASS_RELATE:
            case self::BEAN_ACTION_UNLINK:
            case self::BEAN_ACTION_FILTER_RELATED:
                $action = self::BEAN_ACTION_RELATE;
                break;
            case self::BEAN_ACTION_TEMP_FILE_UPLOAD:
                $urlArgs[self::MODEL_ID_VAR] = 'temp';
            case self::BEAN_ACTION_ATTACH_FILE:
            case self::BEAN_ACTION_DOWNLOAD_FILE:
                $action = self::BEAN_ACTION_FILE;
                break;
            case self::BEAN_ACTION_DUPLICATE_CHECK:
                $urlArgs[self::MODEL_ID_VAR] = $this->getCurrentAction();
            case self::MODEL_ACTION_DELETE:
            case self::MODEL_ACTION_UPDATE:
            case self::MODEL_ACTION_CREATE:
            case self::MODEL_ACTION_RETRIEVE:
                if (isset($urlArgs[self::MODEL_ACTION_VAR])) {
                    unset($urlArgs[self::MODEL_ACTION_VAR]);
                }
                break;
            default:
                $action = $this->getCurrentAction();
        }
        if ($action !== NULL && empty($urlArgs[self::MODEL_ACTION_VAR])) {
            $urlArgs[self::MODEL_ACTION_VAR] = $action;
        }
        return parent::configureURL($urlArgs);
    }

    /**
     * @inheritdoc
     */
    protected function configureAction($action, array $arguments = array()) {
        $urlArgs = $this->getUrlArgs();
        if (isset($urlArgs[self::BEAN_ACTION_ARG1_VAR])) unset($urlArgs[self::BEAN_ACTION_ARG1_VAR]);
        if (isset($urlArgs[self::BEAN_ACTION_ARG2_VAR])) unset($urlArgs[self::BEAN_ACTION_ARG2_VAR]);
        if (isset($urlArgs[self::BEAN_ACTION_ARG3_VAR])) unset($urlArgs[self::BEAN_ACTION_ARG3_VAR]);
        if (!empty($arguments)) {
            switch ($action) {
                case self::BEAN_ACTION_TEMP_FILE_UPLOAD:
                case self::BEAN_ACTION_ATTACH_FILE:
                    $this->_upload = true;
                case self::BEAN_ACTION_RELATE:
                case self::BEAN_ACTION_DOWNLOAD_FILE:
                case self::BEAN_ACTION_UNLINK:
                case self::BEAN_ACTION_CREATE_RELATED:
                case self::BEAN_ACTION_FILTER_RELATED:
                    if (isset($arguments[0])) {
                        $urlArgs[self::BEAN_ACTION_ARG1_VAR] = $arguments[0];
                        if (isset($arguments[1])) {
                            $urlArgs[self::BEAN_ACTION_ARG2_VAR] = $arguments[1];
                            if (isset($arguments[2])) {
                                $urlArgs[self::BEAN_ACTION_ARG3_VAR] = $arguments[2];
                            }
                        }
                    }
            }
        }
        $this->setUrlArgs($urlArgs);
        parent::configureAction($action, $arguments);
    }

    /**
     * System friendly name for subscribing to a record
     * @return self
     */
    public function follow(): AbstractSugarBeanEndpoint {
        return $this->subscribe();
    }

    /**
     * System friendly name for unsubscribing to a record
     * @return self
     */
    public function unfollow(): AbstractSugarBeanEndpoint {
        return $this->unsubscribe();
    }

    /**
     * Human friendly method name for Link action
     * @param string $linkName - Relationship Link Name
     * @param string $related_id - ID to Relate
     * @return self
     */
    public function relate(string $linkName, string $related_id): AbstractSugarBeanEndpoint {
        return $this->link($linkName, $related_id);
    }

    /**
     * Another Human Friendly overload, file & files are the same action
     * @return self
     */
    public function files(): AbstractSugarBeanEndpoint {
        return $this->file();
    }

    /**
     * Human friendly overload for downloadFile action
     * @param $field - Name of File Field
     * @return self
     */
    public function getFile($field): AbstractSugarBeanEndpoint {
        return $this->downloadFile($field);
    }

    /**
     * Human friendly overload for filterLink action
     * @param string $linkName - Name of Relationship Link
     * @param bool $count
     * @return self
     */
    public function getRelated(string $linkName, bool $count = false): AbstractSugarBeanEndpoint {
        if ($count) {
            return $this->filterLink($linkName, 'count');
        }
        return $this->filterLink($linkName);
    }

    /**
     * Filter generator for Related Links
     * @param $linkName - Name of Relationship Link
     * @param bool $count - Whether or not to just do a count request
     * @return FilterData
     */
    public function filterRelated(string $linkName, bool $count = false): FilterData {
        $Filter = new FilterData($this);
        $this->setCurrentAction(self::BEAN_ACTION_FILTER_RELATED);
        $args = array($linkName);
        if ($count) {
            $args[] = 'count';
        }
        $this->configureAction($this->action, $args);
        return $Filter;
    }

    /**
     * Mass Related records to current Bean Model
     * @param string $linkName
     * @param array $related_ids
     * @return AbstractSugarBeanEndpoint
     */
    public function massRelate(string $linkName, array $related_ids): AbstractSugarBeanEndpoint {
        $this->setData(array(
            'link_name' => $linkName,
            'ids' => $related_ids
        ));
        return $this->massLink($linkName);
    }

    /**
     * Overloading attachFile dynamic method to handle more functionality for file uploads
     * @param string $fileField
     * @param string $filePath
     * @param bool $deleteOnFail
     * @param string $mimeType
     * @param string $uploadName
     * @return $this
     * @throws \MRussell\REST\Exception\Endpoint\InvalidDataType
     */
    public function attachFile(
        string $fileField,
        string $filePath,
        bool $deleteOnFail = false,
        string $uploadName = '',
        string $mimeType = ''
    ): AbstractSugarBeanEndpoint {
        $this->setCurrentAction(self::BEAN_ACTION_ATTACH_FILE, array($fileField));
        $this->_deleteFileOnFail = $deleteOnFail;
        $this->_upload = true;
        $this->setFile($fileField,$filePath,array(
            'mimeType' => $mimeType,
            'filename' => $uploadName
        ));
        return $this->execute();
    }

    /**
     * Overloading tempFile dynamic method to provide more functionality
     * @param string $fileField
     * @param string $filePath
     * @param bool $deleteOnFail
     * @param string $mimeType
     * @param string $uploadName
     * @return $this
     * @throws \MRussell\REST\Exception\Endpoint\InvalidDataType
     */
    public function tempFile(
        string $fileField,
        string $filePath,
        bool $deleteOnFail = true,
        string $uploadName = '',
        string $mimeType = ''
    ): AbstractSugarBeanEndpoint {
        $this->setCurrentAction(self::BEAN_ACTION_TEMP_FILE_UPLOAD, array($fileField));
        $this->_upload = true;
        $this->_deleteFileOnFail = $deleteOnFail;
        $this->setFile($fileField,$filePath,array(
            'mimeType' => $mimeType,
            'filename' => $uploadName
        ));
        $this->execute();
        return $this;
    }

    /**
     * Setup the query params passed during File Uploads
     * @return array
     */
    protected function configureFileUploadData(): array {
        $data = array(
            'format' => 'sugar-html-json',
            'delete_if_fails' => $this->_deleteFileOnFail,
        );

        if ($this->_deleteFileOnFail) {
            $Client = $this->getClient();
            if ($Client){
                $data['platform'] = $Client->getPlatform();
                $token = $Client->getAuth()->getTokenProp('access_token');
                if ($token){
                    $data['oauth_token'] = $Client->getAuth()->getTokenProp('access_token');
                }
            }

        }
        return $data;
    }

    /**
     * Add a file to the internal Files array to be added to the Request
     * @param $field
     * @param $path,
     * @param array $properties
     * @return $this
     */
    protected function setFile(string $field,string $path, array $properties = []): AbstractSugarBeanEndpoint {
        if (file_exists($path)) {
            $this->_file = array_replace($properties,[
                'field' => $field,
                'path' => $path
            ]);
        }
        return $this;
    }
}
