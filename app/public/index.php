<?php

use Phalcon\Mvc\Micro;
use Dmkit\Phalcon\Auth\Middleware\Micro as AuthMicro;
use Phalcon\Http\Response;

define('BASE_PATH', realpath(__DIR__ . '/../'));
include_once '../bootstrap/autoload.php';
include_once '../bootstrap/mongo.php';

$app = new Micro($di);

$authConfig = [
    'secretKey' => 'D21A78BE0C4783AD281ECF96DD59C2484D1FFAA9', //move to secrets on variables.env
    'payload' => [
        'exp' => 1440,
        'iss' => 'phalcon-jwt-auth'
    ],
    'ignoreUri' => [
        '/api/1/users/login',
    ]
];

// AUTH MICRO
$auth = new AuthMicro($app, $authConfig);

//mock JWT login
$app->post('/api/1/users/login', function () use ($app) {
    $login = $app->request->getJsonRawBody();
    $response = new Response();
    if (isset($login->username)) {
        //validate($login->username, $login->pass) || 403
        $users = \ChiperTalk\Collections\UsersCollection::find([['name' => $login->username]]);
        if (!count($users)) {
            $user = new \ChiperTalk\Collections\UsersCollection();
            $user->name = $login->username;
            $user->read_count = 0;
            $user->create();
        } else {
            $user = $users[0];
        }
        $payload = [
            'sub' => $user->_id . '',
            'username' => $user->name,
            'iat' => time(),
        ];
        $token = $this->auth->make($payload);
        $response->setStatusCode(200);
        $response->setJsonContent(
            [
                'status' => 'OK',
                'data' => $token,
            ]
        );
    } else {
        $response->setStatusCode(400, 'Bad request');
        $response->setJsonContent(
            [
                'status' => 'ERROR',
                'data' => 'Field username is required',
            ]
        );
    }
    return $response;
});

$app->get('/api/1/messages', function () use ($app) {
    $username = $app->auth->data('username');
    /**@var ChiperTalk\Collections\UsersCollection $user */
    $user = \ChiperTalk\Collections\UsersCollection::find([['name' => $username]])[0];
    $user->read_count++;
    $user->save();

    /**@var ChiperTalk\Collections\MessagesCollection[] $messages */
    $messages = ChiperTalk\Collections\MessagesCollection::find([['user' => $username], 'sort' => ['date' => -1]]);

    $response = new Response();
    $data = [
        'messages' => [],
        'read_count' => $user->read_count,
    ];
    foreach ($messages as $message) {
        $data['messages'][date('Y-m-d\TH:i:s', $message->date)] = $message->message;
    }
    $response->setStatusCode(200);
    $response->setJsonContent($data);
    return $response;

});

$app->post('/api/1/messages', function () use ($app) {
    $data = $app->request->getJsonRawBody();
    $response = new Response();

    if (isset($data->message)) {

        $message = new ChiperTalk\Collections\MessagesCollection();
        $message->user = $app->auth->data('username');
        $message->message = $data->message;
        $message->date = time();
        $message->create();


        $response->setStatusCode(201, 'Created');
        $response->setJsonContent(
            [
                'status' => 'OK',
            ]
        );
    } else {
        $response->setStatusCode(400, 'Bad request');
        $response->setJsonContent(
            [
                'status' => 'ERROR',
                'data' => 'Field message is required',
            ]
        );
    }
    return $response;

});

$app->handle();

