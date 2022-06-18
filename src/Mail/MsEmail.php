<?php

namespace Poseidonphp\MsGraphLite\Mail;
use Poseidonphp\MsGraphLite\MsGraphApi;

class MsEmail
{
    private string $apiPath;
    public string $upn;
    public string $subject;
    public array $sender;
    public array $from;
    public string $body;
    public array $toRecipients;
    public bool $isRead;
    public string $id;

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
        if(array_key_exists('isRead', $message)) {
            $this->isRead = $message['isRead'];
        }
        if(array_key_exists('toRecipients', $message)) {
            $this->toRecipients = $message['toRecipients'];
        }
        if(array_key_exists('subject', $message)) {
            $this->subject = $message['subject'];
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

}
