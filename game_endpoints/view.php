<?php

$app->get('/player/{player}/info', function($player) use ($app) {
    $player = $app['player-repo']->findByIdentifier($player);
    return $app['converter-service']->toJSON($player);
});

$app->get('/board/{board}/pieces', function($board) use ($app) {
    $pieces = $app['piece-repo']->findByBoard($app['board-repo']->findByIdentifier($board));
    return $app['converter-service']->batchToJSON($pieces);
});

$app->get('/board/{board}/tiles', function($board) use ($app) {
    $board = $app['board-repo']->findByIdentifier($board);
    $board->tiles = $app['tile-repo']->findByBoard($board);
    foreach($board->tiles as $tile) {
        $tile->planets = $app['planet-repo']->findByTile($tile);
    }
    return $app['converter-service']->toJSON($board);
});

$app->get('/game/{game}/history', function($game) use ($app) {
    $game = $app['game-repo']->findByIdentifier($game);
    $game->turns = $app['turn-repo']->findCompletedByGame($game);
    foreach($game->turns as $turn) {
        $turn->orders = $app['order-repo']->findByTurn($turn);
        foreach($turn->orders as $order) {
            $order->resolution = $app['resolution-repo']->findByOrder($order);
        }
    }
    return $app['converter-service']->toJSON($game);
});

$app->get('/player/{player}/currentOrders', function($player) use ($app) {
    $player = $app['player-repo']->findByIdentifier($player);
    $game = $app['game-repo']->findByIdentifier($player->gameId);
    $orders = $app['order-repo']->findForPlayerAndTurn($player, $app['turn-repo']->getCurrentForGame($game));
    return $app['converter-service']->batchToJSON($orders);
});

$app->get('/game/{game}/settings', function($game) use ($app) {
    $game = $app['game-repo']->findByIdentifier($game);
    $game->pieceTypes = $app['piece-type-repo']->findAll();
    $game->players = $app['player-repo']->findByGame($game);
    //$game->orderTypes = $app['order-type-repo']->findAll();

    return $app['converter-service']->toJSON($game);
});