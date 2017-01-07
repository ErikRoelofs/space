<?php

$app->get('/test/add', function () use ($app) {
    $entity = $app['temp-repo']->createNew();
    $entity->bla1 = mt_rand(1,10000);
    $entity->bla2 = mt_rand(1,10000);
    $entity->bla3 = mt_rand(1,10000);
    $app['temp-repo']->add($entity);
    return $app['converter-service']->toJSON($entity);
});

$app->get('/test/{id}/remove', function ($id) use ($app) {
    $app['temp-repo']->remove($app['temp-repo']->findByIdentifier($id));
    return '';
});

$app->get('/test/{id}', function ($id) use ($app) {
    $entity = $app['temp-repo']->findByIdentifier($id);
    return $app['converter-service']->toJSON($entity);
});

$app->get('/test/{id}/randomize', function ($id) use ($app) {
    $entity = $app['temp-repo']->findByIdentifier($id);
    $entity->bla1 = mt_rand(1,10000);
    $entity->bla2 = mt_rand(1,10000);
    $entity->bla3 = mt_rand(1,10000);
    $app['temp-repo']->update($entity);
    return $app['converter-service']->toJSON($app['temp-repo']->findByIdentifier($id));
});