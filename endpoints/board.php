<?php

$app->post('/board', function () use ($app) {
    $entity = $app['converter-service']->fromJSON('board', file_get_contents('php://input'));
    $app['board-repo']->add($entity);
    return $app['converter-service']->toJSON($entity);
});

$app->delete('/board/{id}', function ($id) use ($app) {
    $app['board-repo']->remove($app['board-repo']->findByIdentifier($id));
    return '';
});

$app->get('/board/{id}', function ($id) use ($app) {
    $entity = $app['board-repo']->findByIdentifier($id);
    return $app['converter-service']->toJSON($entity);
});

$app->put('/test/{id}', function ($id) use ($app) {
    $entity = $app['board-repo']->findByIdentifier($id);
    $app['board-repo']->update($entity);
    return $app['converter-service']->toJSON($app['board-repo']->findByIdentifier($id));
});