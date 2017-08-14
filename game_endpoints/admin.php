<?php

/**
 * Needs better security.
 */

$app->get('/admin/piecetypes', function() use ($app) {
    if($app['user']->id != 1) {
        return new \Symfony\Component\HttpFoundation\Response("Cannot access this", 403);
    }
    $types = $app['piece-types-service']->loadPieceTypes();
    foreach($types as $type) {
        $app['piece-type-repo']->add($type);
    }
    return 'done';
});

$app->get('/admin/game/{id}/next', function($id) use ($app) {
    if($app['user']->id != 1) {
        return new \Symfony\Component\HttpFoundation\Response("Cannot access this", 403);
    }
    $game = $app['game-service']->buildGame($id);
    $endService = $app['end-of-turn-service']->endRound($game);
    return '';
});
