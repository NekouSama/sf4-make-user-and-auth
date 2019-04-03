<?php
namespace App\External\Websocket;

//require('Chat.php');
use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;
use App\External\Websocket\Chat;


//require dirname(__DIR__) . '/vendor/autoload.php';

$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new Chat()
        )
    ),
    8080
);

$server->run();
