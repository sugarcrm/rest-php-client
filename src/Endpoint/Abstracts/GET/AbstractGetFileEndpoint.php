<?php
/**
 * ©[2016] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace SugarAPI\SDK\Endpoint\Abstracts\GET;

use SugarAPI\SDK\Endpoint\Abstracts\AbstractEndpoint;
use SugarAPI\SDK\Request\GETFile;
use SugarAPI\SDK\Response\File as FileResponse;

abstract class AbstractGetFileEndpoint extends AbstractEndpoint
{
    /**
     * The directory in which to download the File
     * @var string
     */
    protected $downloadDir = null;

    public function __construct($url, array $options = array())
    {
        $this->setRequest(new GETFile());
        $this->setResponse(new FileResponse($this->Request->getCurlObject()));
        parent::__construct($url, $options);
    }

    protected function configureResponse()
    {
        $this->Response->setDestinationPath($this->downloadDir);
        parent::configureResponse();
    }

    /**
     * Set the download directory for the File the Endpoint is retrieving
     * @param $path
     * @return $this
     */
    public function downloadTo($path)
    {
        $this->downloadDir = $path;
        return $this;
    }

    /**
     * Get the download directory for the File the Endpoint is retrieving
     * @return string
     */
    public function getDownloadDir()
    {
        return $this->downloadDir;
    }
}
