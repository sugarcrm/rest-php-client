<?php

namespace Sugarcrm\REST\Endpoint;

use GuzzleHttp\Promise\Utils;
use GuzzleHttp\Psr7\Response;
use Sugarcrm\REST\Endpoint\Abstracts\AbstractSugarBeanEndpoint;

/**
 * Metadata Endpoint provides access to the defined Metadata of the system
 * @package Sugarcrm\REST\Endpoint
 */
class Note extends Module
{
    public const NOTE_ACTION_MULTI_ATTACH = 'multiAttach';

    public const NOTES_FILE_FIELD = 'filename';

    public const NOTES_ATTACHMENTS_FIELD = 'attachments';

    protected $actions = [
        self::NOTE_ACTION_MULTI_ATTACH => 'POST'
    ];

    protected $_beanName = 'Notes';

    private $_attachments = [
        'add' => [],
        'delete' => [],
        'create' => []
    ];

    /**
     * @inheritDoc
     * Add in handling for Multi Attachment Action, since it is multiple requests
     * @param array $urlArgs
     * @return string
     */
    protected function configureURL(array $urlArgs): string
    {
        if ($this->getCurrentAction() == self::NOTE_ACTION_MULTI_ATTACH) {
            //Set ID Var to temp - :module/temp
            $urlArgs[self::MODEL_ID_VAR] = 'temp';
            //Set action to file - :module/temp/file
            $urlArgs[self::MODEL_ACTION_VAR] = self::BEAN_ACTION_FILE;
            //Set action arg1 to filename - :module/temp/file/filename
            $urlArgs[self::BEAN_ACTION_ARG1_VAR] = self::NOTES_FILE_FIELD;
        }
        return parent::configureURL($urlArgs);
    }

    /**
     * Pass in an array of files, to attach to current(or new) Bean
     * @param array $files
     * @return $this
     */
    public function multiAttach(array $files, bool $async = true)
    {
        $parsed = $this->parseFiles($files);
        if (!empty($parsed)) {
            $this->setCurrentAction(self::NOTE_ACTION_MULTI_ATTACH);
            $promises = [];
            foreach ($parsed as $file) {
                $this->setFile(self::NOTES_FILE_FIELD, $file['path'], array(
                    'filename' => $file['name']
                ));
                $this->_upload = true;
                if ($async) {
                    $promises[] = $this->asyncExecute()->getPromise();
                } else {
                    $this->execute();  // @codeCoverageIgnore
                }
            }
            if ($async) {
                $responses = Utils::unwrap($promises);
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
        foreach ($files as $file) {
            if (is_string($file)) {
                $filePath = $file;
                $fileName = basename($filePath);
            } elseif (is_array($file)) {
                $filePath = $file['path'];
                $fileName = $file['name'] ?? null;
            } elseif (is_object($file)) {
                $filePath = $file->path;
                $fileName = $file->name ?? null;
            }
            if (file_exists($filePath)) {
                $parsed[] = [
                    'path' => $filePath,
                    'name' => $fileName ?? basename($filePath)
                ];
            }
        }
        return $parsed;
    }

    /**
     * @return AbstractSugarBeanEndpoint|Note|void
     */
    public function clear()
    {
        $this->resetAttachments();
        return parent::clear();
    }

    /**
     * Reset the attachments link to default blank values
     * @return $this
     */
    public function resetAttachments()
    {
        $this->_attachments = [
            'add' => [],
            'delete' => [],
            'create' => []
        ];
        $this->getData()->offsetUnset(self::NOTES_ATTACHMENTS_FIELD);
        return $this;
    }

    /**
     * @param Response $response
     * @return void
     */
    public function parseResponse(Response $response): void
    {
        parent::parseResponse($response);
        if ($response->getStatusCode() == 200) {
            switch ($this->getCurrentAction()) {
                case self::MODEL_ACTION_UPDATE:
                case self::MODEL_ACTION_CREATE:
                    $this->resetAttachments();
                    break;
                case self::NOTE_ACTION_MULTI_ATTACH:
                    $body = $this->getResponseBody();
                    if (isset($body['record'])) {
                        $note = $body['record'];
                        $note['filename_guid'] = $body['record']['id'];
                        $this->_attachments['create'][] = $note;
                    }
                    break;
            }
        }
    }

    /**
     * Add ID(s) of attachments to be deleted. Does not make the API call, call execute once ready
     * @param string|array $id
     * @return $this
     */
    public function deleteAttachments($id)
    {
        if (!is_array($id)) {
            $id = [$id];
        }
        array_push($this->_attachments['delete'], ...$id);
        return $this;
    }

    /**
     * @return bool
     */
    protected function hasAttachmentsChanges()
    {
        foreach ($this->_attachments as $key => $values) {
            if (!empty($values)) {
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
        $data = parent::configurePayload();
        if ($this->hasAttachmentsChanges()) {
            $data = $this->getData()->set(self::NOTES_ATTACHMENTS_FIELD, $this->_attachments)->toArray();
        }
        return $data;
    }
}
