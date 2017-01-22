<?php
/*
 * Sometime too hot the eye of heaven shines
 */

require 'vendor/autoload.php';

use Pad\Foundation\Socket\Server;

$config_file = require __DIR__.'/../../config.php';    //require config file

$socket = new server();
$socket->start();