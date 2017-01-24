<?php
/*
 * Sometime too hot the eye of heaven shines
 */

namespace Pad\Foundation\Socket\Observers;

use Pad\Foundation\Interfaces\ObserverInterface;
use Pad\Foundation\Redis\Redis;

class History implements ObserverInterface
{
    protected $redis;

    protected $historyStack = 'discuss_history';

    public function __construct()
    {
        $redis = new Redis();
        $this->redis = $redis->getClient();
    }

    public function handle($data)
    {
        $message = array_shift($data);

        $insert = json_encode($message->insert);

        if ($message->pad_id == 'discuss') {
            $this->redis->rpush($this->historyStack, $insert);    //push insert to redis list
        }
    }
}