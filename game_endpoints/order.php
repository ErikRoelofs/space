<?php

$app->post('/order/{player}/place/{type}', function($player, $type) use ($app) {
    $orderDetails = json_decode(file_get_contents('php://input'), true);
    $player = $app['player-repo']->findByIdentifier($player);

    $app['order-service']->createOrder($player, $type, $orderDetails);
});

$app->delete('/order/{player}/{order}', function($player, $order) use ($app) {

});

$app->get('/order/tactical/{player}/{tile}', function($player, $tile) use ($app) {
    $player = $app['player-repo']->findByIdentifier($player);
    $tile = $app['tile-repo']->findByIdentifier($tile);
    $game = $app['game-service']->buildGame($tile->gameId);

    $pieces = $app['order-service']->getOrder('tactical')->getPotentialPiecesForOrder($tile, $player, $game);

    return $app['converter-service']->batchToJson($pieces);
});