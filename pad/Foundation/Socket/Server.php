<?php

/*
 * Sometime too hot the eye of heaven shines
 */

namespace Pad\Foundation\Socket;

use Pad\Foundation\Redis\Redis;
use Pad\Foundation\Socket\Events\Insert;
use Pad\Foundation\Socket\Observers\Deliver;
use Pad\Foundation\Socket\Observers\History;
use Pad\Foundation\Socket\Observers\Update;
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

        switch ($data->type)
        {
            case 'init':
                $this->pad->join($sender, $data->pad_id);
                break;

            case 'message':
                $insertEvent = new Insert([$data, $sender, $socketServer]);
                $insertEvent->registerObserver(new Deliver());
                $insertEvent->registerObserver(new Update());
                $insertEvent->registerObserver(new History());

                fire($insertEvent);
                break;

            case 'history':
                $redis = new Redis();
                $list = $redis->getClient()->lrange('discuss_history', 0, -1);

                foreach ($list as $k => $v) {
                    $message = '{"insert":'.$v.',"pad_id":"discuss.history"}';
                    $socketServer->push($sender, $message);
                    usleep(50000);
                }
                break;
        }
    }




    public function onOpen($socketServer, $request)
    {

    }


    public function onClose($socketServer, $fd)
    {
        echo "client-{$fd} is closed\n";
        $this->pad->remove($fd);
    }


    public function start()
    {
        $this->socketServer->start();
    }

}
