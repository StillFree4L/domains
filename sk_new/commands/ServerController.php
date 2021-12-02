<?php
namespace app\commands;

use app\daemons\ChatServer;
use yii\console\Controller;

class ServerController extends Controller
{
    public function actionStart()
    {
        $server = new ChatServer();
        $server->port = 1024;

        $server->on(ChatServer::EVENT_WEBSOCKET_OPEN, function($e) use($server) {
            echo "Server started at port " . $server->port;
        });

        $server->start();
    }
}