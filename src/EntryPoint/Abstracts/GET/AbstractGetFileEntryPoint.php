<?php
/**
 * ©[2016] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace SugarAPI\SDK\EntryPoint\Abstracts\GET;

use SugarAPI\SDK\EntryPoint\Abstracts\AbstractEntryPoint;
use SugarAPI\SDK\Request\GETFile;
use SugarAPI\SDK\Response\File as FileResponse;

abstract class AbstractGetFileEntryPoint extends AbstractEntryPoint {

    /**
     * The directory in which to download the File
     * @var string
     */
    protected $downloadDir = null;

    public function __construct($url, array $options = array()){
        $this->setRequest(new GETFile());
        $this->setResponse(new FileResponse($this->Request->getCurlObject()));
        parent::__construct($url, $options);
    }

    public function configureResponse(){
        $this->Response->setDestinationPath($this->downloadDir);
        parent::configureResponse();
    }

    /**
     * Set the download directory for the File the EntryPoint is retrieving
     * @param $path
     * @return $this
     */
    public function downloadTo($path){
        $this->downloadDir = $path;
        return $this;
    }

    /**
     * Get the download directory for the File the EntryPoint is retrieving
     * @return string
     */
    public function getDownloadDir(){
        return $this->downloadDir;
    }

}