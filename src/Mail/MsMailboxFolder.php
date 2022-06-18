<?php

namespace Poseidonphp\MsGraphLite\Mail;
use Poseidonphp\MsGraphLite\MsGraphApi;

class MsMailboxFolder
{
    public string $upn;
    public string $id;
    public string $displayName;
    public string $parentFolderId;
    public int $childFolderCount;
    public int $unreadItemCount;
    public int $totalItemCount;
    public bool $isHidden;

    public function __construct($upn, array $folder) {
        $this->id = $folder['id'];
        $this->upn = $upn;
        if(array_key_exists('displayName', $folder)) {
            $this->displayName = $folder['displayName'];
        }
        if(array_key_exists('parentFolderId', $folder)) {
            $this->parentFolderId = $folder['parentFolderId'];
        }
        if(array_key_exists('childFolderCount', $folder)) {
            $this->childFolderCount = $folder['childFolderCount'];
        }
        if(array_key_exists('unreadItemCount', $folder)) {
            $this->unreadItemCount = $folder['unreadItemCount'];
        }
        if(array_key_exists('totalItemCount', $folder)) {
            $this->totalItemCount = $folder['totalItemCount'];
        }
        if(array_key_exists('isHidden', $folder)) {
            $this->isHidden = $folder['isHidden'];
        }

    }

}
