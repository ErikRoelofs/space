<?php

$app->get('/admin/piecetypes', function() use ($app) {
    $types = $app['piece-types-service']->loadPieceTypes();
    foreach($types as $type) {
        $app['piece-type-repo']->add($type);
    }
    return 'done';
});

$app->get('/admin/game/{id}', function($id) use ($app) {
    $game = $app['game-service']->buildGame($id);

    //return json_encode($game);
    return $app['converter-service']->toJson($game);
});
