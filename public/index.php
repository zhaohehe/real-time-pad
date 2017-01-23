<?php
/*
 * Sometime too hot the eye of heaven shines
 */

$app = require '../pad/Foundation/bootstrap.php';

$app->get('/pad', 'Pad\Controllers\PadController:home');

$app->get('/pad/private', 'Pad\Controllers\PadController:secret');

$app->get('/pad/{id}', 'Pad\Controllers\PadController:show');

$app->run();