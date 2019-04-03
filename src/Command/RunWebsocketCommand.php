<?php
namespace App\Command;

use Symfony\Component\Process\Process;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;
use App\External\Websocket\Chat;

class RunWebsocketCommand extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'run:websocket';

    protected function configure()
    {
        // ...
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        //Lance le serveur
        $server = IoServer::factory(
            new HttpServer(
                new WsServer(
                    new Chat()
                )
            ),
            8080
        );
        $server->run();
    }
}
