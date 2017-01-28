<?php

$app->get('/game/{id}', function($id) use ($app) {
    $game = $app['game-service']->buildGame($id);
    return $app['converter-service']->toJson($game);
});