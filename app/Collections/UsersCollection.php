<?php

namespace ChiperTalk\Collections;

use Phalcon\Mvc\MongoCollection;

class UsersCollection extends MongoCollection
{
    /**@var string */
    public $name;
    /**@var int */
    public $read_count;

    public function getSource()
    {
        return 'users';
    }
}