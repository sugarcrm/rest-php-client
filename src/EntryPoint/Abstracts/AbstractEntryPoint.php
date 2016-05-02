<?php
/**
 * ©[2016] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace SugarAPI\SDK\EntryPoint\Abstracts;


use SugarAPI\SDK\EntryPoint\Interfaces\EPInterface;
use SugarAPI\SDK\Exception\EntryPoint\InvalidRequestException;
use SugarAPI\SDK\Exception\EntryPoint\InvalidURLException;
use SugarAPI\SDK\Exception\EntryPoint\RequiredDataException;
use SugarAPI\SDK\Exception\EntryPoint\RequiredOptionsException;
use SugarAPI\SDK\Response\Interfaces\ResponseInterface;
use SugarAPI\SDK\Request\Interfaces\RequestInterface;

/**
 * Class AbstractEntryPoint
 * @package SugarAPI\SDK\EntryPoint\Abstracts
 */
abstract class AbstractEntryPoint implements EPInterface {

    /**
     * Whether or not Authentication is Required
     * @var bool
     */
    protected $_AUTH_REQUIRED = true;

    /**
     * The URL for the EntryPoint
     * - When configuring URL you define URL Parameters with $variables
     *      Examples:
     *          - Forecasts/$record_id
     * - $module Variable is a keyword to place the Module property into the URL
     *      Examples:
     *          - $module/$record
     * - Options property is used to replace variables in the order in which they are passed
     *
     * @var string
     */
    protected $_URL;

    /**
     * An array of Required Data properties that should be passed in the Request
     * @var array
     */
    protected $_REQUIRED_DATA = array();

    /**
     * The required type of Data to be given to the EntryPoint. If none, different types can be passed in.
     * @var string
     */
    protected $_DATA_TYPE;

    /**
     * The configured URL for the EntryPoint
     * @var string
     */
    protected $Url;

    /**
     * The initial URL passed into the EntryPoint
     * @var
     */
    protected $baseUrl;

    /**
     * The passed in Options for the EntryPoint
     * - If $module variable is used in $_URL static property, then 1st option will be used as Module
     * @var array
     */
    protected $Options = array();

    /**
     * The data being passed to the API EntryPoint
     * @var mixed - array||stdClass
     */
    protected $Data;

    /**
     * The Request Object, used by the EntryPoint to submit the data
     * @var RequestInterface
     */
    protected $Request;

    /**
     * The Response Object, returned by the Request Object
     * @var ResponseInterface
     */
    protected $Response;

    /**
     * Access Token for authentication
     * @var string
     */
    protected $accessToken;


    public function __construct($baseUrl,array $options = array()){
        $this->baseUrl = $baseUrl;

        if (!empty($options)) {
            $this->setOptions($options);
        }
        $this->configureURL();
    }

    /**
     * @inheritdoc
     */
    public function setOptions(array $options){
        $this->Options = $options;
        $this->configureURL();
        return $this;
    }

    /**
     * @inheritdoc
     * @throws RequiredDataException - When passed in data contains issues
     */
    public function setData($data){
        $this->Data = $data;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setAuth($accessToken) {
        $this->accessToken = $accessToken;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setUrl($url) {
        $this->Url = $url;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setRequest(RequestInterface $Request) {
        $this->Request = $Request;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setResponse(ResponseInterface $Response) {
        $this->Response = $Response;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getOptions(){
        return $this->Options;
    }

    /**
     * @inheritdoc
     */
    public function getData(){
        return $this->Data;
    }

    /**
     * @inheritdoc
     */
    public function getUrl(){
        return $this->Url;
    }

    /**
     * @inheritdoc
     */
    public function getResponse(){
        return $this->Response;
    }

    /**
     * @inheritdoc
     */
    public function getRequest(){
        return $this->Request;
    }

    /**
     * @inheritdoc
     * @param null $data - short form data for EntryPoint, which is configure by configureData method
     * @return $this
     * @throws InvalidRequestException
     * @throws InvalidURLException
     */
    public function execute($data = NULL){
        $data =  ($data === NULL?$this->Data:$data);
        $this->configureData($data);
        if (is_object($this->Request)) {
            $this->configureRequest();
            $this->Request->send();
            $this->configureResponse();
        }else{
            throw new InvalidRequestException(get_called_class(),"Request property not configured");
        }
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function authRequired() {
        return $this->_AUTH_REQUIRED;
    }

    /**
     * Verifies URL and Data are setup, then sets them on the Request Object
     * @throws InvalidURLException
     * @throws RequiredDataException
     */
    protected function configureRequest(){
        if ($this->verifyUrl()) {
            $this->Request->setURL($this->Url);
        }
        if ($this->verifyData() && !empty($this->Data)) {
            $this->Request->setBody($this->Data);
        }
        $this->configureAuth();
    }

    /**
     * Verifies URL and Data are setup, then sets them on the Request Object
     * @throws InvalidURLException
     * @throws RequiredDataException
     */
    protected function configureResponse(){
        if (is_object($this->Response)){
            $this->Response->setCurlResponse($this->Request->getCurlResponse());
        }
    }

    /**
     * Configures the authentication header on the Request object
     */
    protected function configureAuth(){
        if ($this->authRequired()) {
            $this->Request->addHeader('OAuth-Token', $this->accessToken);
        }
    }

    /**
     * Configures Data for the EntryPoint. Used mainly as an override function on implemented EntryPoints.
     * @var $data
     */
    protected function configureData($data){
        if (!empty($this->_REQUIRED_DATA)&&is_array($data)){
            $data = $this->configureDefaultData($data);
        }
        $this->setData($data);
    }

    /**
     * Configure Defaults on a Data array based on the Required Data property
     * @param array $data
     * @return array
     */
    protected function configureDefaultData(array $data){
        foreach($this->_REQUIRED_DATA as $property => $value){
            if (!isset($data[$property]) && $value!==NULL){
                $data[$property] = $value;
            }
        }
        return $data;
    }

    /**
     * Configures the URL, by updating any variable placeholders in the URL property on the EntryPoint
     * - Replaces $module with $this->Module
     * - Replaces all other variables starting with $, with options in the order they were given
     */
    protected function configureURL(){
        $url = $this->_URL;
        if ($this->requiresOptions()) {
            foreach($this->Options as $key => $option){
                $url = preg_replace('/(\$.*?[^\/]*)/',$option,$url,1);
            }
        }
        $url = $this->baseUrl.$url;
        $this->setUrl($url);
    }

    /**
     * Verify if URL is configured properly
     * @return bool
     * @throws InvalidURLException
     */
    protected function verifyUrl(){
        $UrlArray = explode("?",$this->Url);
        if (strpos($UrlArray[0],"$") !== FALSE){
            throw new InvalidURLException(get_called_class(),"Configured URL is ".$this->Url);
        }
        return true;
    }

    /**
     * Validate the Data property on the EntryPoint
     * @return bool
     * @throws RequiredDataException
     */
    protected function verifyData(){
        if (isset($this->_DATA_TYPE)||!empty($this->_DATA_TYPE)) {
            $this->verifyDataType();
        }
        if (!empty($this->_REQUIRED_DATA)){
            $this->verifyRequiredData();
        }
        return true;
    }

    /**
     * Validate DataType on the EntryPoint object
     * @return bool
     * @throws RequiredDataException
     */
    protected function verifyDataType(){
        if (gettype($this->Data) !== $this->_DATA_TYPE) {
            throw new RequiredDataException(get_called_class(),"Valid DataType is {$this->_DATA_TYPE}");
        }
        return true;
    }

    /**
     * Validate Required Data for the EntryPoint
     * @return bool
     * @throws RequiredDataException
     */
    protected function verifyRequiredData(){
        $errors = array();
        foreach($this->_REQUIRED_DATA as $property => $defaultValue){
            if (!isset($this->Data[$property])){
                $errors[] = $property;
            }
        }
        if (count($errors)>0){
            throw new RequiredDataException(get_called_class(),"Missing data for ".implode(",",$errors));
        }
        return true;
    }

    /**
     * Checks if EntryPoint URL contains requires Options
     * @return bool
     */
    protected function requiresOptions(){
        return strpos($this->_URL,"$") === FALSE?FALSE:TRUE;
    }

}