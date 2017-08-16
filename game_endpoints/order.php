<?php

$app->post('/order/{player}/place/{type}', function($player, $type) use ($app) {
    $player = $app['player-repo']->findByIdentifier($player);
    if(!$app['player-service']->canControlPlayer($player)) {
        return new \Symfony\Component\HttpFoundation\Response("Cannot place an order for this player.", 403);
    }

    $orderDetails = json_decode(file_get_contents('php://input'), true);
    $app['order-service']->createOrder($player, $type, $orderDetails);

    return '';
});

$app->delete('/order/{player}/{order}', function($player, $order) use ($app) {
    $player = $app['player-repo']->findByIdentifier($player);
    if(!$app['player-service']->canControlPlayer($player)) {
        return new \Symfony\Component\HttpFoundation\Response("Cannot delete an order for this player.", 403);
    }

    $order = $app['order-repo']->findByIdentifier($order);

    if($order->ownerId != $player->id) {
        throw new \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException("This order does not belong to this player.");
    }
    $app['order-service']->revertOrder($order);

    return '';
});

$app->get('/order/tactical/{player}/{tile}/moveable', function($player, $tile) use ($app) {
    $player = $app['player-repo']->findByIdentifier($player);
    if(!$app['player-service']->canControlPlayer($player)) {
        return new \Symfony\Component\HttpFoundation\Response("Cannot view data for this player.", 403);
    }

    $tile = $app['tile-repo']->findByIdentifier($tile);
    $game = $app['game-service']->buildGame($tile->gameId);

    $pieces = $app['order-service']->getOrder('tactical')->getPotentialPiecesForOrder($tile, $player, $game);

    return $app['converter-service']->batchToJson($pieces);
});

$app->get('/order/tactical/{player}/{tile}/buildable', function($player, $tile) use ($app) {
    $player = $app['player-repo']->findByIdentifier($player);
    if(!$app['player-service']->canControlPlayer($player)) {
        return new \Symfony\Component\HttpFoundation\Response("Cannot view data for this player.", 403);
    }

    $tile = $app['tile-repo']->findByIdentifier($tile);
    $game = $app['game-service']->buildGame($tile->gameId);
    $tile = $game->findTile($tile->id);

    $pieceTypes = $app['order-service']->getOrder('tactical')->getBuildablePieceTypesForOrder($tile, $player, $game);

    return $app['converter-service']->batchToJson($pieceTypes);
});

$app->post('/order/{player}/ready', function($player) use ($app) {
    $player = $app['player-repo']->findByIdentifier($player);
    if(!$app['player-service']->canControlPlayer($player)) {
        return new \Symfony\Component\HttpFoundation\Response("Cannot place an order for this player.", 403);
    }

    $app['player-service']->setPlayerReady($player);
    return '';
});

$app->post('/order/{player}/notReady', function($player) use ($app) {
    $player = $app['player-repo']->findByIdentifier($player);
    if(!$app['player-service']->canControlPlayer($player)) {
        return new \Symfony\Component\HttpFoundation\Response("Cannot place an order for this player.", 403);
    }

    $app['player-service']->setPlayerNotReady($player);
    return '';
});
