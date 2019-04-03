<?php
namespace App\External\Websocket;

//require dirname(__DIR__) . '/vendor/autoload.php';

use Ratchet\ConnectionInterface;
use App\External\Websocket\SaveInDB;
use Ratchet\MessageComponentInterface;

class Chat implements MessageComponentInterface
{
    protected $clients;
    protected $clientsPseudo;

    public function __construct()
    {
        $this->clients = new \SplObjectStorage;
        $this->clientsPseudo = $_POST['pseudo'] ?? 'unknow';
    }

    public function onOpen(ConnectionInterface $conn)
    {
        // Store the new connection to send messages to later
        var_dump($this->clientsPseudo);
        $this->clients->attach($conn);
        foreach ($this->clients as $client) {
            echo $client->resourceId . " est connecté\n";
        }
        echo "New connection! ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        $numRecv = count($this->clients) - 1;
        $objJSON = json_decode($msg);
        $msg = $objJSON->{"text"};
        echo sprintf(
            'Connection %d sending message "%s" to %d other connection%s' . "\n",
            $from->resourceId,
            $msg,
            $numRecv,
            $numRecv == 1 ?'' : 's'
        );

        //$db = new SaveInDB;
        //$db->addMessageInDB($msg);

        $sendBack["id"] = $objJSON->{"id"};
        $sendBack["message"] = $msg;
        foreach ($this->clients as $client) {
            $from === $client ?$sendBack["fromMyself"] = 1 : $sendBack["fromMyself"] = 0;
            $client->send(json_encode($sendBack));
        }
    }

    public function onClose(ConnectionInterface $conn)
    {
        // The connection is closed, remove it, as we can no longer send it messages
        $this->clients->detach($conn);

        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo "An error has occurred: {$e->getMessage()}\n";

        $conn->close();
    }
}
