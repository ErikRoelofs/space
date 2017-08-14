<?php

$app->get('/game/{id}', function($id) use ($app) {
    $game = $app['game-service']->buildGame($id);
    return $app['converter-service']->toJson($game);
});

$app->get('/game/{id}/player/{player}', function($id, $player) use ($app) {
	$player = $app['player-repo']->findByIdentifier($player);
    if(!$app['player-service']->canControlPlayer($player)) {
        return new \Symfony\Component\HttpFoundation\Response("Cannot view player info for this player", 403);
    }

    $initial = $app['resource-service']->getInitialResources($player);
	$current = $app['resource-service']->getCurrentResources($player);

	$orders = $app['orders-service']->getActiveOrdersForPlayer($player);

	return json_encode(['resources'=>['initial' => $initial, 'current' => $current],'orders' => $orders, 'player' => $app['converter-service']->toJsonObject($player)]);
});

$app->get('/log/{id}', function($id) use ($app) {
   $log = $app['log-repo']->findByIdentifier($id);

   return $app['log-expander']->expand($log);
});
