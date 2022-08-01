<?php

namespace Poseidonphp\MsGraphLite\Mail;
use Illuminate\Support\Collection;
use Poseidonphp\MsGraphLite\MsGraphApi;

class MsEmail
{
    private string $apiPath;
    public string $upn;
    public string $subject;
    public array $sender;
    public array $from;
    public string $body;
    public string $bodyPreview;
    public array $toRecipients;
    public array $ccRecipients;
    public array $bccRecipients;
    public bool $isRead;
    public string $id;
    public bool $hasAttachments;
    public string $importance;
    public string $parentFolderId;

    public array $attachments;

    public function __construct($upn, array $message) {
        $this->id = $message['id'];
        $this->upn = $upn;

        if(array_key_exists('sender', $message)) {
            $this->sender = $message['sender'];
        }
        if(array_key_exists('from', $message)) {
            $this->from = $message['from'];
        }
        if(array_key_exists('body', $message)) {
            $this->body = $message['body'];
        }
        if(array_key_exists('bodyPreview', $message)) {
            $this->bodyPreview = $message['bodyPreview'];
        }
        if(array_key_exists('isRead', $message)) {
            $this->isRead = $message['isRead'];
        }
        if(array_key_exists('toRecipients', $message)) {
            $this->toRecipients = $message['toRecipients'];
        }
        if(array_key_exists('ccRecipients', $message)) {
            $this->ccRecipients = $message['ccRecipients'];
        }
        if(array_key_exists('bccRecipients', $message)) {
            $this->bccRecipients = $message['bccRecipients'];
        }
        if(array_key_exists('subject', $message)) {
            $this->subject = $message['subject'];
        }
        if(array_key_exists('hasAttachments', $message)) {
            $this->hasAttachments = $message['hasAttachments'];
//            $this->listAttachments();
        }
        if(array_key_exists('importance', $message)) {
            $this->importance = $message['importance'];
        }
        if(array_key_exists('parentFolderId', $message)) {
            $this->parentFolderId = $message['parentFolderId'];
        }

    }

    public function moveToFolder($destinationId) {
        MsGraphApi::doPostApi($this->upn . '/messages/' . $this->id . '/move', ['destinationId' => $destinationId]);
    }

    public function markAsRead() {
        MsGraphApi::doPatchApi($this->upn . '/messages/' . $this->id, ['isRead' => true]);
    }

    public function delete() {
        MsGraphApi::doDeleteApi($this->upn . '/messages/' . $this->id);
    }

    // Adds new categories without removing old
    public function addCategories(array|string $categories = []) {
        //
    }

    /** Sets categories to specified; removing any from the message that are not passed */
    public function setCategories(array|string $categories = []) {
        //
    }

    public function listAttachments(): void {
        $attachments = MsGraphApi::doGetApi($this->upn . '/mailFolders/' . $this->parentFolderId . '/messages/' . $this->id . '/attachments');
        $attachments_collection = [];
        foreach($attachments->collect('value') as $attachment) {
            $attachments_collection[] = $attachment;
        }
        $this->attachments = $attachments_collection;
    }

    public function getAttachment($attachment_id, $storage_disk = null, $storage_path =  null) {
        $attachment = MsGraphApi::doGetApi($this->upn . '/mailFolders' . $this->parentFolderId . '/messages/' . $this->id . '/attachments/' . $attachment_id . '/$value');
        if($storage_path && $storage_disk) {
            //
        } else {
            return $attachment;
        }
    }


}
