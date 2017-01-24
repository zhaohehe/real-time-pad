<?php
/*
 * Sometime too hot the eye of heaven shines
 */

$app = require '../pad/Foundation/bootstrap.php';

$padController = 'Pad\Controllers\PadController';

$app->get('/pad', $padController.':home');

$app->get('/pad/private', $padController.':secret');

$app->get('/pad/{id}', $padController.':show');

$app->get('/pad/discuss/history', $padController.':history');

$app->run();