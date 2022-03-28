<?php

/**
 * ©[2022] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace Sugarcrm\REST\Endpoint\Abstracts;


use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Utils;
use MRussell\REST\Endpoint\Data\DataInterface;
use MRussell\REST\Endpoint\Data\EndpointData;
use MRussell\REST\Endpoint\Interfaces\EndpointInterface;
use MRussell\REST\Endpoint\ModelEndpoint;
use PHPUnit\Util\Filter;
use Sugarcrm\REST\Endpoint\Data\FilterData;
use Sugarcrm\REST\Endpoint\SugarEndpointInterface;
use Sugarcrm\REST\Endpoint\Traits\CompileRequestTrait;

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
abstract class AbstractSugarBeanEndpoint extends ModelEndpoint implements SugarEndpointInterface {
    use CompileRequestTrait;
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

    const BEAN_MODULE_VAR = 'module';

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
    protected $module = "";

    /**
     * Whether or not a file upload is occurring
     * @var bool
     */
    private $upload = true;

    /**
     * Files waiting to be attached to record
     * @var array
     */
    private $_files = array();

    public function __construct(array $options = array(), array $properties = array()) {
        parent::__construct($options, $properties);
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
        if (isset($args[0])) {
            $this->setModule($args[0]);
            $args[self::BEAN_MODULE_VAR] = $this->module;
            unset($args[0]);
        }
        if (isset($args[1])) {
            $this->set(static::$_MODEL_ID_KEY, $args[1]);
            $args[self::MODEL_ID_VAR] = $args[1];
            unset($args[1]);
        }
        return parent::setUrlArgs($args);
    }

    /**
     * Set the Sugar Module currently being used
     * @param $module
     * @return $this
     */
    public function setModule($module): AbstractSugarBeanEndpoint {
        $this->module = $module;
        return $this;
    }

    /**
     * Get the Sugar Module currently configured
     * @return string
     */
    public function getModule(): string {
        return $this->module;
    }

    //    /**
    //     * Configure Uploads on Request
    //     * @inheritdoc
    //     * @codeCoverageIgnore
    //     */
    //    protected function configureRequest(Request $Request)
    //    {
    //        $Request = parent::configureRequest($Request);
    //        return $this->configureUploads($Request);
    //    }

    /**
     * @TODO - Need to work out how to manage file uploads with Guzzle (Multipart data)
     * Configure the Uploads Data on the Request Object
     * @param Request $Request
     * @return Request
     */
    protected function configureUploads(Request $Request) {
    }

    /**
     * Add a reset for Upload Settings
     * @inheritdoc
     * @codeCoverageIgnore
     */
    public function parseResponse(Response $response): void {
        $this->resetUploads();
        if ($this->response->getStatusCode() == "200") {
            switch ($this->getCurrentAction()) {
                case self::BEAN_ACTION_TEMP_FILE_UPLOAD:
                    $body = $this->getResponseBody();
                    if (isset($body['record'])) {
                        $this->reset();
                        $this->update(array(
                            'filename_guid' => $body['record']['id'],
                            'filename' => $body['filename']['guid']
                        ));
                    }
                    return;
            }
        }
        parent::parseResponse($response);
    }

    /**
     * Reset the Upload Settings back to defaults
     */
    protected function resetUploads() {
        if ($this->upload) {
            $this->getData()->reset();
        }
        $this->upload = true;
        $this->_files = array();
    }

    /**
     * Redefine some Actions to another Action, for use in URL
     * @inheritdoc
     */
    protected function configureURL(array $options): string {
        $action = $this->getCurrentAction();
        switch ($action) {
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
            case self::BEAN_ACTION_DUPLICATE_CHECK:
                $action = NULL;
                break;
        }
        if ($action !== NULL) {
            $options[self::MODEL_ACTION_VAR] = $action;
        } else {
            if (isset($options[self::MODEL_ACTION_VAR])) {
                unset($options[self::MODEL_ACTION_VAR]);
            }
        }
        return parent::configureURL($options);
    }

    /**
     * @inheritdoc
     */
    protected function configureAction($action, array $arguments = array()) {
        $options = $this->getUrlArgs();
        $options[self::BEAN_MODULE_VAR] = $this->getModule();
        if (isset($options[self::BEAN_ACTION_ARG1_VAR])) unset($options[self::BEAN_ACTION_ARG1_VAR]);
        if (isset($options[self::BEAN_ACTION_ARG2_VAR])) unset($options[self::BEAN_ACTION_ARG2_VAR]);
        if (isset($options[self::BEAN_ACTION_ARG3_VAR])) unset($options[self::BEAN_ACTION_ARG3_VAR]);
        if (!empty($arguments)) {
            switch ($action) {
                case self::BEAN_ACTION_DUPLICATE_CHECK:
                    $options[self::MODEL_ID_VAR] = $action;
                    break;
                case self::BEAN_ACTION_TEMP_FILE_UPLOAD:
                case self::BEAN_ACTION_ATTACH_FILE:
                    $this->upload = true;
                case self::BEAN_ACTION_RELATE:
                case self::BEAN_ACTION_DOWNLOAD_FILE:
                case self::BEAN_ACTION_UNLINK:
                case self::BEAN_ACTION_CREATE_RELATED:
                case self::BEAN_ACTION_FILTER_RELATED:
                    if (isset($arguments[0])) {
                        $options[self::BEAN_ACTION_ARG1_VAR] = $arguments[0];
                        if (isset($arguments[1])) {
                            $options[self::BEAN_ACTION_ARG2_VAR] = $arguments[1];
                            if (isset($arguments[2])) {
                                $options[self::BEAN_ACTION_ARG3_VAR] = $arguments[2];
                            }
                        }
                    }
            }
        }
        $this->setUrlArgs($options);
        parent::configureAction($action, $arguments);
    }

    /**
     * @inheritdoc
     */
    protected function syncFromApi(array $model) {
        switch ($this->action) {
            case self::BEAN_ACTION_FAVORITE:
            case self::BEAN_ACTION_UNFAVORITE:
                $this->reset();
                $this->update($model);
                break;
            default:
                parent::syncFromApi($model);
        }
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
        string $mimeType = '',
        string $uploadName = ''
    ): AbstractSugarBeanEndpoint {
        $this->setCurrentAction(self::BEAN_ACTION_ATTACH_FILE, array($fileField));
        $this->configureFileUploadData($deleteOnFail);
        $this->addFile($fileField, array(
            'path' => $filePath,
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
        bool $deleteOnFail = false,
        string $mimeType = '',
        string $uploadName = ''
    ): AbstractSugarBeanEndpoint {
        $model = $this->toArray();
        $idKey = $this->modelIdKey();
        if (isset($model[$idKey])) {
            $this->reset();
        }
        $this->set($idKey, 'temp');
        $this->setCurrentAction(self::BEAN_ACTION_TEMP_FILE_UPLOAD, array($fileField));
        $this->configureFileUploadData($deleteOnFail);
        $this->addFile($fileField, array(
            'path' => $filePath,
            'mimeType' => $mimeType,
            'filename' => $uploadName
        ));
        return $this->execute();
    }

    /**
     * @return $this
     * @throws \MRussell\REST\Exception\Endpoint\InvalidRequest
     */
    public function duplicateCheck(): AbstractSugarBeanEndpoint {
        $action = self::BEAN_ACTION_DUPLICATE_CHECK;
        $this->setCurrentAction($action);
        $idKey = $this->modelIdKey();
        $id = $this[$idKey];
        $this[$idKey] = $action;
        $this->setData($this->toArray());
        $this->execute();
        $this[$idKey] = $id;
        return $this;
    }

    /**
     * @param bool $deleteOnFail
     * @throws \MRussell\REST\Exception\Endpoint\InvalidDataType
     */
    protected function configureFileUploadData(bool $deleteOnFail = true): void {
        $data = array(
            'format' => 'sugar-html-json',
            'delete_if_fails' => $deleteOnFail,
        );

        if ($deleteOnFail) {
            $Client = $this->getClient();
            if ($Client){
                $token = $Client->getAuth()->getToken();
                $data['oauth_token'] = $token['access_token'];
            }

        }
        $this->setData($data);
    }

    /**
     * Add a file to the internal Files array to be added to the Request
     * @param $name
     * @param array $properties
     * @return $this
     */
    protected function addFile($name, array $properties): AbstractSugarBeanEndpoint {
        if (isset($properties['path'])) {
            $this->upload = true;
            $this->_files[$name] = $properties;
        }
        return $this;
    }
}
