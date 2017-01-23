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

    protected $pages = [
        'home',
        'instruction',    //说明文档
        //'discuss',        //公共频道
    ];

    public function __construct()
    {
        $socket_server = config('web_socket.server');

        $this->socketServer = new swoole_websocket_server($socket_server['host'], $socket_server['port']);
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
            $this->deliverMessage($sender, json_encode($data->insert), json_encode($data->content), $data->pad_id, $socketServer);

        } elseif ($data->type == 'init') {
            $this->pad->join($sender, $data->pad_id);
            $padId = $this->pad->getContent($data->pad_id)->pad_id;
            $content = $this->pad->getContent($data->pad_id)->content;

            $message = '{"insert":'.$content.',"pad_id":"'.$padId.'"}';

            $socketServer->push($sender, $message);
        }
    }



    protected function deliverMessage($sender, $insert, $content, $padId, $socketServer)
    {
        $members = $this->pad->getMembersById($padId);

        $message = '{"insert":'.$insert.',"pad_id":"'.$padId.'"}';

        foreach ($members as $key => $member) {
            if ($member != $sender) {
                $socketServer->push($member, $message);
            }
        }
        //update pad content
        if (! in_array($padId, $this->pages) || config('page_update')) {
            $this->pad->updateContent($content, $padId);
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
