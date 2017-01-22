<?php

$app->get('/admin/piecetypes', function() use ($app) {
    $types = $app['piece-types-service']->loadPieceTypes();
    foreach($types as $type) {
        $app['piece-type-repo']->add($type);
    }
    return 'done';
});