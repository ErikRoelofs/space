<?php

function registerEndpoints($app, $endpoint, $name, $repo)
{

    $app->post('/' . $endpoint, function () use ($app, $endpoint, $name, $repo) {
        $entity = $app['converter-service']->fromJSON($name, file_get_contents('php://input'));
        $entity = $app[$repo]->add($entity);
        return $app['converter-service']->toJSON($entity);
    });

    $app->delete('/' . $endpoint . '/{id}', function ($id) use ($app, $endpoint, $name, $repo) {
        $app[$repo]->remove($app[$repo]->findByIdentifier($id));
        return '';
    });

    $app->get('/' . $endpoint . '/{id}', function ($id) use ($app, $endpoint, $name, $repo) {
        $entity = $app[$repo]->findByIdentifier($id);
        return $app['converter-service']->toJSON($entity);
    });

    $app->put('/' . $endpoint . '/{id}', function ($id) use ($app, $endpoint, $name, $repo) {
        $entity = $app['converter-service']->fromJSON($name, file_get_contents('php://input'));
        $entity->id = $id;
        $app[$repo]->update($entity);
        return $app['converter-service']->toJSON($app[$repo]->findByIdentifier($id));
    });
}

$register = [ 'game', 'board', 'givenOrder', 'piece', 'pieceType', 'planet', 'player', 'resolution', 'tile', 'turn'];
foreach($register as $toRegister) {
    registerEndpoints($app, $toRegister, $toRegister, $toRegister . '-repo');
}