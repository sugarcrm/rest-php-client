<?php

namespace Sugarcrm\REST\Endpoint;

use GuzzleHttp\Psr7\Stream;
use MRussell\REST\Endpoint\Data\DataInterface;
use MRussell\REST\Exception\Endpoint\InvalidData;
use GuzzleHttp\Promise\Utils;
use GuzzleHttp\Psr7\Response;
use Sugarcrm\REST\Endpoint\Abstracts\AbstractSugarBeanEndpoint;
use Sugarcrm\REST\Endpoint\Traits\ParseFilesTrait;
use Sugarcrm\REST\Endpoint\Traits\NoteAttachmentsTrait;

/**
 * Metadata Endpoint provides access to the defined Metadata of the system
 * @package Sugarcrm\REST\Endpoint
 */
class Note extends Module
{
    use NoteAttachmentsTrait {
        resetAttachments as private resetAttachmentsProp;
    }
    use ParseFilesTrait;

    public const NOTE_ACTION_MULTI_ATTACH = 'multiAttach';

    public const NOTES_FILE_FIELD = 'filename';

    public const NOTES_ATTACHMENTS_FIELD = 'attachments';

    protected $actions = [
        self::NOTE_ACTION_MULTI_ATTACH => 'POST',
    ];

    protected $_beanName = 'Notes';

    /**
     * @inheritDoc
     * Add in handling for Multi Attachment Action, since it is multiple requests
     */
    protected function configureURL(array $urlArgs): string
    {

        if ($this->getCurrentAction() === self::NOTE_ACTION_MULTI_ATTACH) {
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
     * @return $this
     */
    public function multiAttach(array $files, bool $async = true)
    {
        $parsed = $this->parseFiles($files);
        if (!empty($parsed)) {
            $this->setCurrentAction(self::NOTE_ACTION_MULTI_ATTACH);
            $promises = [];
            foreach ($parsed as $file) {
                $this->setFile(self::NOTES_FILE_FIELD, $file['path'], [
                    'filename' => $file['name'],
                ]);
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
        $this->resetAttachmentsProp();
        $this->getData()->offsetUnset($this->getAttachmentsLinkField());
        return $this;
    }

    protected function parseResponse(Response $response): void
    {
        parent::parseResponse($response);
        if ($response->getStatusCode() == 200) {
            switch ($this->getCurrentAction()) {
                case self::MODEL_ACTION_UPDATE:
                case self::MODEL_ACTION_CREATE:
                    $this->resetAttachments();
                    break;
                case self::NOTE_ACTION_MULTI_ATTACH:
                    $this->parseAttachmentUploadResponse($this->getResponseBody());
                    break;
            }
        }
    }

    /**
     * @return array|Stream|mixed|DataInterface|string|null
     * @throws InvalidData
     */
    protected function configurePayload()
    {
        $data = parent::configurePayload();
        return $this->configureAttachmentsPayload($data);
    }
}
