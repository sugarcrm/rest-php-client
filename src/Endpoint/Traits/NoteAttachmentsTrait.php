<?php

namespace Sugarcrm\REST\Endpoint\Traits;

use Sugarcrm\REST\Endpoint\Note;

/**
 * Trait NoteAttachmentsTrait
 * @package Sugarcrm\REST\Endpoint\Traits
 * Manages the Attachments relationship to Notes
 */
trait NoteAttachmentsTrait
{
    private $_attachments = [
        'add' => [],
        'delete' => [],
        'create' => [],
    ];

    protected function getAttachmentsLinkField(): string
    {
        return Note::NOTES_ATTACHMENTS_FIELD;
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
            'create' => [],
        ];
        return $this;
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
        foreach ($this->_attachments as $values) {
            if (!empty($values)) {
                return true;
            }
        }

        return false;
    }

    protected function parseAttachmentUploadResponse(array $body): void
    {
        if (isset($body['record'])) {
            $note = $body['record'];
            $note['filename_guid'] = $body['record']['id'];
            $this->_attachments['create'][] = $note;
        }
    }

    protected function configureAttachmentsPayload(&$data)
    {
        if ($this->hasAttachmentsChanges()) {
            $data[$this->getAttachmentsLinkField()] = $this->_attachments;
        }

        return $data;
    }
}
