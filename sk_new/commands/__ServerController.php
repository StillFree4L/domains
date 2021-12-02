<?php 
namespace app\commands;

use jones\wschat\components\Chat;
use jones\wschat\components\ChatManager;
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;

class ServerController extends \yii\console\Controller
{
    public function actionRun()
    {
        $server = IoServer::factory(new HttpServer(new WsServer(new Chat(new ChatManager()))), 8080);
        $server->run();
    }
}
?>