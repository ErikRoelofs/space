<?php

$app->post('/order/{player}/place/{type}', function($player, $type) use ($app) {
    $orderDetails = json_decode(file_get_contents('php://input'), true);
    $player = $app['player-repo']->findByIdentifier($player);

    $app['order-service']->createOrder($player, $type, $orderDetails);

    return '';
});

$app->delete('/order/{player}/{order}', function($player, $order) use ($app) {
    $order = $app['order-repo']->findByIdentifier($order);
    $player = $app['player-repo']->findByIdentifier($player);

    if($order->ownerId != $player->id) {
        throw new \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException("This is not your order.");
    }
    $app['order-service']->revertOrder($order);

    return '';
});

$app->get('/order/tactical/{player}/{tile}/moveable', function($player, $tile) use ($app) {
    $player = $app['player-repo']->findByIdentifier($player);
    $tile = $app['tile-repo']->findByIdentifier($tile);
    $game = $app['game-service']->buildGame($tile->gameId);

    $pieces = $app['order-service']->getOrder('tactical')->getPotentialPiecesForOrder($tile, $player, $game);

    return $app['converter-service']->batchToJson($pieces);
});

$app->get('/order/tactical/{player}/{tile}/buildable', function($player, $tile) use ($app) {
    $player = $app['player-repo']->findByIdentifier($player);
    $tile = $app['tile-repo']->findByIdentifier($tile);
    $game = $app['game-service']->buildGame($tile->gameId);
    $tile = $game->findTile($tile->id);

    $pieceTypes = $app['order-service']->getOrder('tactical')->getBuildablePieceTypesForOrder($tile, $player, $game);

    return $app['converter-service']->batchToJson($pieceTypes);
});
