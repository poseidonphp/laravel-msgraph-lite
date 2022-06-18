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

    public function getMail(): Collection {
        $query = [];
        if(count($this->filters) > 0) {
            $query[] = '$filter=' . join(' and ', $this->filters);
        }
        $query[] = '$select=' . join(',', $this->selectFields);


        $messages = MsGraphApi::doGetApi($this->upn . '/messages?' . join('&', $query));
        $emails = new Collection();
        foreach($messages->collect('value') as $message) {
            $emails->push(new MsEmail($this->upn, $message));
        }

        return $emails;
    }


    public function getFolders() {
        $msFolders = MsGraphApi::doGetApi($this->upn . '/mailFolders');
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
