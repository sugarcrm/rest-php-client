<?php

namespace Sugarcrm\REST\Endpoint;

use GuzzleHttp\Promise\Utils;
use GuzzleHttp\Psr7\Response;
use MRussell\REST\Endpoint\Interfaces\EndpointInterface;
use Sugarcrm\REST\Endpoint\Abstracts\AbstractSugarBeanEndpoint;

class Note extends AbstractSugarBeanEndpoint
{
    const NOTE_ACTION_MULTI_ATTACH = 'multiAttach';

    const NOTES_FILE_FIELD = 'filename';

    const NOTES_ATTACHMENTS_FIELD = 'attachments';

    protected $actions = [
        self::NOTE_ACTION_MULTI_ATTACH => 'POST'
    ];

    protected $_beanName = 'Notes';

    private $_attachments = [
        'add' => [],
        'delete' => [],
        'create' => []
    ];

    protected function configureURL(array $urlArgs): string {
        $multiAttach = false;
        //Change action to Temp File, so that URL is setup correct
        if ($this->getCurrentAction() == self::NOTE_ACTION_MULTI_ATTACH){
            $multiAttach = true;
            $this->setCurrentAction(self::BEAN_ACTION_TEMP_FILE_UPLOAD,[self::NOTES_FILE_FIELD]);
            $urlArgs = $this->getUrlArgs();
        }
        $url = parent::configureURL($urlArgs);
        //Put Action Back to Multi Attach so that Response handling works
        if ($multiAttach){
            $this->setCurrentAction(self::NOTE_ACTION_MULTI_ATTACH);
        }
        return $url;
    }

    /**
     * Pass in an array of files, to attach to current(or new) Bean
     * @param array $files
     * @return $this
     */
    public function multiAttach(array $files,bool $async = true){
        $parsed = $this->parseFiles($files);
        if (!empty($parsed)){
            $this->setCurrentAction(self::NOTE_ACTION_MULTI_ATTACH);
            $idKey = $this->modelIdKey();
            $oldId = $this->get($idKey);
            $this->set($idKey, 'temp');
            $this->configureFileUploadData(true);
            $promises = [];
            foreach($parsed as $file){
                $this->setFile(self::NOTES_FILE_FIELD,$file['path'],array(
                    'filename' => $file['name']
                ));
                if ($async){
                    $promises[] = $this->asyncExecute()->getPromise();
                } else {
                    $this->execute();
                }
            }
            if ($async){
                $responses = Utils::unwrap($promises);
            }
            if (!empty($oldId)){
                $this->set($idKey, $oldId);
            } else {
                $this->offsetUnset($idKey);
            }
            $this->save();
        }
        return $this;
    }

    /**
     * Parse files array into standard format
     * @param array $files
     * @return array
     */
    protected function parseFiles(array $files)
    {
        $parsed = [];
        foreach($files as $file){
            if (is_string($file)){
                $filePath = $file;
                $fileName = basename($filePath);
            }elseif (is_array($file)){
                $filePath = $file['path'];
                $fileName = $file['name'] ?? basename($filePath);
            } elseif (is_object($file)) {
                $filePath = $file->path;
                $fileName = $file->name ?? basename($filePath);
            }
            if (file_exists($filePath)){
                $parsed[] = [
                    'path' => $filePath,
                    'name' => $fileName
                ];
            }
        }
        return $parsed;
    }

    /**
     * @return void
     */
    public function resetAttachments()
    {
        $this->_attachments = [
            'add' => [],
            'delete' => [],
            'create' => []
        ];
    }

    /**
     * @param Response $response
     * @return void
     */
    public function parseResponse(Response $response): void{
        parent::parseResponse($response);
        if ($response->getStatusCode() == 200){
            switch ($this->getCurrentAction()){
                case self::MODEL_ACTION_UPDATE:
                case self::MODEL_ACTION_CREATE:
                    $this->resetAttachments();
                    break;
                case self::NOTE_ACTION_MULTI_ATTACH:
                    $body = $this->getResponseBody();
                    if (isset($body['record'])){
                        $note = $body['record'];
                        $note['filename_guid'] = $body['record']['id'];
                        $this->_attachments['create'][] = $note;
                    }
                    break;
            }
        }
    }

    /**
     * @param string|array $id
     * @return $this
     */
    public function deleteAttachments($id)
    {
        if (!is_array($id)){
            $id = [$id];
        }
        array_push($this->_attachments['delete'],...$id);
        return $this;
    }

    /**
     * @return bool
     */
    protected function hasAttachmentsChanges()
    {
        foreach($this->_attachments as $key => $values)
        {
            if (!empty($values)){
                return true;
            }
        }
        return false;
    }

    /**
     * @return array|\GuzzleHttp\Psr7\Stream|mixed|\MRussell\REST\Endpoint\Data\DataInterface|string|null
     * @throws \MRussell\REST\Exception\Endpoint\InvalidData
     */
    protected function configurePayload()
    {
        if ($this->hasAttachmentsChanges()){
            $this->getData()->set(self::NOTES_ATTACHMENTS_FIELD,$this->_attachments);
        }
        return parent::configurePayload();
    }
}