<?php
namespace Poseidonphp\MsGraphLite\User;

use Poseidonphp\MsGraphLite\MsGraphApi;

class MsUser
{

    public string | null $sn;
    public string | null $givenName;
    public string | null $office;
    public string | null $email;
    public string | null $displayName;
    public string | null $title;
    public string | null $department;
    public string | null $company;
    public string $id;
    public string | null $upn;

    public $groups;

    public static function getUser($username) {
        $user = MsGraphApi::doGetApi($username);
        $obj = new self();
        return $obj->make($user->json());
    }

    public function make($user) {
        $this->id = $user['id'];
        $this->upn = $user['userPrincipalName'];

        if(array_key_exists('surname', $user)) {
            $this->sn = $user['surname'];
        }
        if(array_key_exists('givenName', $user)) {
            $this->givenName = $user['givenName'];
        }
        if(array_key_exists('displayName', $user)) {
            $this->displayName = $user['displayName'];
        }
        if(array_key_exists('mail', $user)) {
            $this->email = $user['mail'];
        }
        if(array_key_exists('company', $user)) {
            $this->company = $user['company'];
        }
        if(array_key_exists('jobTitle', $user)) {
            $this->title = $user['jobTitle'];
        }
        if(array_key_exists('department', $user)) {
            $this->department = $user['department'];
        }
        if(array_key_exists('officeLocation', $user)) {
            $this->office = $user['officeLocation'];
        }
        return $this;

    }

    public function getGroups() {
        $groups = MsGraphApi::doGetApi($this->upn . '/memberOf');
        $this->groups = [];
        foreach($groups->collect('value') as $group) {
            $g = new MsGroup();
            $this->groups[] = $g->make($group);
        }
        return $this->groups;
    }

}
