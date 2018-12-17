<?php

use Phalcon\Di\FactoryDefault;
use Phalcon\Mvc\Collection\Manager;
use Phalcon\Db\Adapter\MongoDB\Client;

$di = new FactoryDefault();

// Initialise the mongo DB connection.
$di->setShared('mongo', function () {
    /** @var \Phalcon\DiInterface $this */
    $dsn = 'mongodb://mongo:27017';

    $mongo = new Client($dsn);

    return $mongo->selectDatabase('ChiperTalk');
});

// Collection Manager is required for MongoDB
$di->setShared('collectionManager', function () {
    return new Manager();
});