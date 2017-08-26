<?php

$app->get('/chat/channel/{id}', function($id) use ($app) {
    $channel = $app['chat-service']->buildChannel($id);
    return $app['converter-service']->toJson($channel);
});

$app->get('/chat/myChannels', function() use ($app) {
    return $app['converter-service']->batchToJson($app['channel-repo']->findForUser($app['user']));
});

$app->post('/chat/send/{id}', function($id) use ($app) {
    $data = json_decode(file_get_contents('php://input'), true);
    return $app['converter-service']->toJson($app['chat-service']->sendMessage($id, $data['message']));
});
