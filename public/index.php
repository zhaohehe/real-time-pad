<?php
/*
 * Sometime too hot the eye of heaven shines
 */

require '../vendor/autoload.php';

$app = new \Slim\App();    //create app

$container = $app->getContainer();    //get container

$container['view'] = function ($container) {    // register template on container
    $view = new \Slim\Views\Twig(__DIR__ .'/../views/', [
        'cache' => false
    ]);
    $view->addExtension(new \Slim\Views\TwigExtension(
        $container['router'],
        $container['request']->getUri()
    ));

    return $view;
};


$app->get('/pad', function ($request, $response, $args) use ($app) {

    $padId = str_random();    //generate random pad id
    return $response->withStatus(200)->withHeader('Location', 'pad/'.$padId);
});


$app->get('/pad/{id}', function ($request, $response, $args) {
    if ($args['id'] != 'B6ZqW0IB7F0l5Ok4') {
        exit('invalid pad!');
    }
    $pad = new \Pad\Models\Pad();

    return $this->view->render($response, 'index.twig', [
        'padId' => $args['id'],
        'content' => (string)$pad->getContent($args['id'])->content
    ]);
})->setName('pad');


$app->run();