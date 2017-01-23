<?php
/*
 * Sometime too hot the eye of heaven shines
 */

namespace Pad\Foundation\Socket\Observers;

use Pad\Foundation\Interfaces\ObserverInterface;
use Pad\Models\Pad;

class Update implements ObserverInterface
{
    protected $pad;

    protected $pages = [
        'home',
        'instruction',    //说明文档
        //'discuss',        //公共频道
    ];

    public function __construct()
    {
        $this->pad = new Pad();
    }

    public function handle($data)
    {
        list($message, $sender, $socketServer) = $data;

        $content = json_encode($message->content);
        $padId = $message->pad_id;

        //update pad content
        if (! in_array($padId, $this->pages) || config('page_update')) {
            $this->pad->updateContent($content, $padId);
        }
    }
}