<?php
/**
 * ©[2016] SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.
 */

namespace SugarAPI\SDK\Response;

use SugarAPI\SDK\Response\Abstracts\AbstractResponse;

class File extends AbstractResponse
{
    /**
     * The name of the File from Response
     * @var string
     */
    protected $fileName;

    /**
     * File Path for response
     * @var string
     */
    protected $destinationPath;

    public function __construct($curlRequest, $curlResponse = null, $destination = null)
    {
        parent::__construct($curlRequest, $curlResponse);
        $this->setDestinationPath($destination);
    }

    /**
     * @inheritdoc
     * Extract Filename from Headers
     * @param mixed $curlResponse
     */
    public function setCurlResponse($curlResponse)
    {
        parent::setCurlResponse($curlResponse);
        if (!$this->error) {
            if (empty($this->fileName)) {
                $this->extractFileName();
            }
            $this->writeFile();
        }
    }


    /**
     * Configure the Destination path to store the File response
     * @param null $destination
     * @return self
     */
    public function setDestinationPath($destination = null)
    {
        if (empty($destination)) {
            $destination = sys_get_temp_dir().'/SugarAPI';
        }
        $this->destinationPath = $destination;
        return $this;
    }

    /**
     * Extract the filename from the Headers, and store it in filename property
     */
    protected function extractFileName(){
        foreach (explode("\r\n", $this->headers) as $header)
        {
            if (strpos($header, 'filename') !== false && strpos($header, 'Content-Disposition') !== false)
            {
                $fileName = substr($header, (strpos($header, "=")+1));
                $this->setFileName($fileName);
                break;
            }
        }
    }

    /**
     * Set the Filename for response to be saved to
     * @param $fileName
     * @return self
     */
    public function setFileName($fileName)
    {
        $fileName = preg_replace("([^\w\s\d\-_~,;\[\]\(\).])", '', $fileName);
        $fileName = preg_replace("([\.]{2,})", '', $fileName);
        $this->fileName = $fileName;
        return $this;
    }

    /**
     * Return the filename found in response
     * @return mixed
     */
    public function getFileName()
    {
        return $this->fileName;
    }

    /**
     * Write the downloaded file
     * @return string|boolean - False if not written
     */
    public function writeFile()
    {
        if (!empty($this->fileName)) {
            if (!file_exists($this->destinationPath)) {
                mkdir($this->destinationPath, 0777);
            }
            $file = $this->file();
            $fileHandle = fopen($file, 'w+');
            fwrite($fileHandle, $this->body);
            fclose($fileHandle);
            return $file;
        } else {
            return false;
        }
    }

    /**
     * Return the full File path, where Response was stored
     * @return string
     */
    public function file()
    {
        return rtrim($this->destinationPath, DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR.$this->fileName;
    }
}
