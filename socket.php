<?php
/*
 * Sometime too hot the eye of heaven shines
 */

require 'vendor/autoload.php';

use Pad\Foundation\Socket\Server;

$socket = new server();
$socket->start();