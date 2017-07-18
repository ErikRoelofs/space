<?php

require_once __DIR__.'/vendor/autoload.php';

$app = new Silex\Application();
$app['debug'] = true;
error_reporting(E_ALL);
ini_set('display_errors', true);


require_once('services.php');

require_once('endpoints/rest.php');
require_once('game_endpoints/view.php');
require_once('game_endpoints/order.php');
require_once('game_endpoints/admin.php');
require_once('test_endpoints/test.php');

$app->get('/load/pieces', function() use ($app) {
    $s = new \Plu\Service\PieceTypesService();
    $pieces = $s->loadPieceTypes();
    foreach($pieces as $piece) {
        $app['piece-type-repo']->add($piece);
    }
    return 'done';
});

$app->get('/newgame', function() use ($app) {
    $app['new-game-service']->newGame(6);
});

$app->run();
