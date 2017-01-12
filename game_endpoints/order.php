<?php

$app->post('/order/{player}/place/{type}', function($player, $type) use ($app) {
    $orderDetails = json_decode(file_get_contents('php://input'), true);
    $player = $app['player-repo']->findByIdentifier($player);

    $app['order-service']->createOrder($player, $type, $orderDetails);
});

$app->delete('/order/{player}/{order}', function($player, $order) use ($app) {

});