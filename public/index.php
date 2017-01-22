<?php
/*
 * Sometime too hot the eye of heaven shines
 */

$app = require '../pad/Foundation/bootstrap.php';

$app->get('/pad', function ($request, $response, $args) use ($app) {

    $padId = str_random();    //generate random pad id
    return $response->withStatus(200)->withHeader('Location', 'pad/'.$padId);
});


$app->get('/pad/{id}', function ($request, $response, $args) {
    if ($args['id'] != 'B6ZqW0IB7F0l5Ok4') {
        exit('invalid pad!');
    }
    $pad = new \Pad\Models\Pad();

    $socket_client = $config_file['web_socket']['client'];

    return $this->view->render($response, 'index.twig', [
        'padId' => $args['id'],
        'web_socket' => 'ws://'.$socket_client['host'].':'.$socket_client['port'],
        'content' => (string)$pad->getContent($args['id'])->content
    ]);
})->setName('pad');


$app->run();