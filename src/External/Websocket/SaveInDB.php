<?php
namespace App\External\Websocket;

class SaveInDB
{
    public function addMessageInDB(string $msg)
    {
        $file = 'base.txt';
        $current = file_get_contents($file);
        $current .= $msg . "\n";
        file_put_contents($file, $current);
    }
}
