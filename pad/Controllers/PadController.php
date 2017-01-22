<?php

/*
 * Sometime too hot the eye of heaven shines
 */

namespace Pad\Controllers;

use Pad\Models\Pad;

class PadController extends BaseController
{

    protected $pad;

    public function __construct()
    {
        $this->pad = new Pad();
    }


    public function deliverMessage($sender, $insert, $content, $padId, $socketServer)
    {
        $config_file = require __DIR__.'/../../config.php';    //require config file

        $members = $this->pad->getMembersById($padId);

        foreach ($members as $key => $member) {
            if ($member != $sender) {
                $socketServer->push($member, $insert);
            }
        }
        //update pad content
        if ($padId != 'home_document' || $config_file['home_update']) {
            $this->pad->updateContent($content, $padId);
        }
    }
}