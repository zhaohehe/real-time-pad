<?php

/*
 * Sometime too hot the eye of heaven shines
 */

namespace Pad\Foundation\Socket;

use Pad\Models\Pad;
use swoole_websocket_server;
use Pad\Controllers\PadController;

class Server
{
    private $pad;

    private $belongs = [];

    private $socketServer;

    public function __construct()
    {
        $this->socketServer = new swoole_websocket_server('127.0.0.1', '3890');
        $this->socketServer->set([
            'worker_num' => 8,
            'daemonize'  => false,
        ]);

        $this->pad = new Pad();

        $this->socketServer->on('open', [$this, 'onOpen']);
        $this->socketServer->on('message', [$this, 'message']);
        $this->socketServer->on('close', [$this, 'onClose']);
    }


    public function message($socketServer, $frame)
    {
        $sender = $frame->fd;
        $data = json_decode($frame->data);

//        $this->belongs[] = [
//            'member' => $sender,
//            'pad'    => $data->pad_id
//        ];

        if ($data->type == 'message') {
            $pad = new PadController();
            $pad->deliverMessage($sender, json_encode($data->insert), json_encode($data->content), $data->pad_id, $socketServer);

        } elseif ($data->type == 'init') {
            $this->pad->join($sender, $data->pad_id);
            $content = $this->pad->getContent($data->pad_id)->content;

            $socketServer->push($sender, $content);
        }
    }

    public function onOpen($socketServer, $request)
    {

    }


    public function onClose($socketServer, $fd)
    {
        echo "client-{$fd} is closed\n";
        //$this->pad->remove($fd);
    }


    public function start()
    {
        $this->socketServer->start();
    }

}
