<?php

$app->post('/game', function () use ($app) {
    $entity = $app['converter-service']->fromJSON('game', file_get_contents('php://input'));
    $entity = $app['game-repo']->add($entity);
    return $app['converter-service']->toJSON($entity);
});

$app->delete('/game/{id}', function ($id) use ($app) {
    $app['game-repo']->remove($app['game-repo']->findByIdentifier($id));
    return '';
});

$app->get('/game/{id}', function ($id) use ($app) {
    $entity = $app['game-repo']->findByIdentifier($id);
    return $app['converter-service']->toJSON($entity);
});

$app->put('/game/{id}', function ($id) use ($app) {
    $entity = $app['game-repo']->findByIdentifier($id);
    $app['game-repo']->update($entity);
    return $app['converter-service']->toJSON($app['game-repo']->findByIdentifier($id));
});