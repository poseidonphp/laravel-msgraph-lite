<?php

namespace Poseidonphp\MsGraphLite\Mail;

use Illuminate\Support\Collection;
use Poseidonphp\MsGraphLite\MsGraphApi;

class MsMailbox
{

    public array $selectFields = ['sender', 'subject'];
    private $filters = [];
    private string $upn;

//    private MsGraphApi $api;

    public function __construct($forUserPrincipalName) {
        $this->upn = $forUserPrincipalName;
//        $this->api = new MsGraphApi('/' . $this->upn);
    }


    public function select($fields = ['sender', 'subject']) {
        $this->selectFields = $fields;
        return $this;
    }

    public function filter($filterString) {
        $this->filters[] = $filterString;
        return $this;
    }

    public function unread() {
        $this->filter('isRead ne true');
        return $this;
    }

    public function getMailInFolder($folder_id): Collection {
        return $this->getMail($folder_id);
    }

    public function getMail($in_folder_id = null): Collection {
        $query = [];
        if(count($this->filters) > 0) {
            $query[] = '$filter=' . join(' and ', $this->filters);
        }
        $query[] = '$select=' . join(',', $this->selectFields);

        if($in_folder_id) {
            $messages = MsGraphApi::doGetApi($this->upn . '/mailFolders/' . $in_folder_id . '/messages?' . join('&', $query));
        } else {
            $messages = MsGraphApi::doGetApi($this->upn . '/messages?' . join('&', $query));
        }

        $emails = new Collection();
        foreach($messages->collect('value') as $message) {
            $emails->push(new MsEmail($this->upn, $message));
        }

        return $emails;
    }


    public function getFolders($parent_folder_id) {
        if($parent_folder_id) {
            $msFolders = MsGraphApi::doGetApi($this->upn . '/mailFolders/' . $parent_folder_id . '/childFolders');
        } else {
            $msFolders = MsGraphApi::doGetApi($this->upn . '/mailFolders');
        }

        $folders = new Collection();
        foreach($msFolders->collect('value') as $folder) {
            $folders->push(new MsMailboxFolder($this->upn, $folder));
        }
        return $folders;
    }

    public function getCategories() {
        $msCats = MsGraphApi::doGetApi($this->upn . '/outlook/masterCategories');
        $categories = new Collection();
        foreach($msCats->collect('value') as $category) {
            $categories->push($category);
        }
        return $categories;
    }

}
