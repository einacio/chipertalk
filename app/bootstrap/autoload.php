<?php

use Phalcon\Loader;

$loader = new Loader();

$loader->registerNamespaces(
    [
        'ChiperTalk\Chat' => BASE_PATH . '/Models/',
        'Phalcon' => BASE_PATH . '/Library/Phalcon/',
        'ChiperTalk\Collections' => BASE_PATH . '/Collections/',
        'Dmkit\Phalcon\Auth' => BASE_PATH . '/Library/Phalcon/Auth/',
        'Firebase\JWT' => BASE_PATH . '/Library/FirebaseJWT/',sss
    ]
);

$loader->register();