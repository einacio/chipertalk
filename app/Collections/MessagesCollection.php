<?php

namespace ChiperTalk\Collections;

use Phalcon\Mvc\MongoCollection;

class MessagesCollection extends MongoCollection
{
    /**@var string user name */
    public $user;
    /**@var string */
    public $message;
    /**@var \DateTime */
    public $date;

    public function getSource()
    {
        return 'messages';
    }
}