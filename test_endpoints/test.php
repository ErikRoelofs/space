<?php

$app->get('/test/spacebattle', function() use ($app) {
    $battleService = $app['space-battle-service'];

    $report = $battleService->resolveAllSpaceBattles( $app['game-repo']->findByIdentifier(1));

    print_r($report);
    exit;
});