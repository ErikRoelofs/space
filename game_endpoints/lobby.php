<?php

$app->get('/lobby/myGames', function() use ($app) {
    $player = null;

    $games = $app['game-repo']->findForUser($app['user']);

    $archived = [];
    $active = [];
    foreach($games as $game) {
        if($game->active) {
            $active[] = ['id' => $game->id];
        }
        else {
            $archived[] = ['id' => $game->id];
        }
    }

    return json_encode(['active' => $active, 'archived' => $archived]);

});
