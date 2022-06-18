<?php

namespace Poseidonphp\MsGraphLite\User;

class MsGroup
{
    public string $id;
    public string | null $name;
    public string | null $description;
    public bool $mailEnabled;

    private $raw;

    public function make($group) {
        $this->raw = $group;

        $this->id = $group['id'];
        if(array_key_exists('displayName', $group)) {
            $this->name = $group['displayName'];
        }
        if(array_key_exists('description', $group)) {
            $this->description = $group['description'];
        }

        return $this;
    }

    public function getRaw() {
        return $this->raw;
    }
}
