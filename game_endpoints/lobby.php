<?php

$app->get('/lobby/myGames', function() use ($app) {
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

$app->get('/lobby/openGames', function() use ($app) {
   $games = $app['open-game-repo']->findAll();

   return $app['converter-service']->batchToJson($games);
});

$app->post('/lobby/joinGame/{gameId}', function($gameId) use ($app) {
    $data = json_decode(file_get_contents('php://input'), true);
    $password = $data['password'];

    $openGame = $app['open-game-repo']->findByIdentifier($gameId);
    $lobbyService = $app['lobby-service'];

    if(!$lobbyService->validatePassword($password, $openGame)) {
        return new \Symfony\Component\HttpFoundation\Response("The supplied password is incorrect", 403);
    }
    if(!$lobbyService->userCanJoin($openGame)) {
        return new \Symfony\Component\HttpFoundation\Response("You are already in this game", 400);
    }

    return $app['converter-service']->toJson($lobbyService->joinGame($openGame, $password));

});

$app->post('/lobby/createGame', function() use ($app) {
    $data = json_decode(file_get_contents('php://input'), true);

    $password = $data['password'];
    $vpLimit = $data['vpLimit'];

    $lobbyService = $app['lobby-service'];
    return $app['converter-service']->toJson( $lobbyService->openGame($vpLimit, $password));

});
