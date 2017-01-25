<?php

function makePiece(\Plu\Repository\PieceTypeRepository $repo, $type, $owner) {
    $type = $repo->findByName($type);
    if(!$type) {
        throw new \Exception("No such type: " . $type);
    }
    $piece = new \Plu\Entity\Piece();
    $piece->typeId = $type->id;
    $piece->ownerId = $owner;
    return $piece;
}

$app->get('/test/spacebattle', function() use ($app) {
    $battleService = $app['space-battle-service'];


	$tile = new \Plu\Entity\Tile();
    $pieces = [
		makePiece($app['piece-type-repo'], "Planet", 1),
        makePiece($app['piece-type-repo'], "dreadnought", 1),
        makePiece($app['piece-type-repo'], "destroyer", 1),
        makePiece($app['piece-type-repo'], "destroyer", 1),
        makePiece($app['piece-type-repo'], "destroyer", 1),
        makePiece($app['piece-type-repo'], "destroyer", 1),
        makePiece($app['piece-type-repo'], "destroyer", 1),
        makePiece($app['piece-type-repo'], "destroyer", 1),
        makePiece($app['piece-type-repo'], "fighter", 2),
        makePiece($app['piece-type-repo'], "destroyer", 2),
        makePiece($app['piece-type-repo'], "cruiser", 2),
        makePiece($app['piece-type-repo'], "fighter", 2),
        makePiece($app['piece-type-repo'], "fighter", 2),
        makePiece($app['piece-type-repo'], "fighter", 2),
        makePiece($app['piece-type-repo'], "fighter", 2),
        makePiece($app['piece-type-repo'], "fighter", 2),
        makePiece($app['piece-type-repo'], "fighter", 2),
        makePiece($app['piece-type-repo'], "fighter", 2),

    ];

    $log = new \Plu\Service\Loggers\SpaceBattleLog($tile);

    $report = $battleService->resolve($pieces, $log);

    print_r($report);
    exit;
});

$app->get('/test/groundbattle', function() use ($app) {
    $battleService = $app['ground-battle-service'];

    $tile = new \Plu\Entity\Tile();

    $pieces = [
        makePiece($app['piece-type-repo'], "dreadnought", 1),
        makePiece($app['piece-type-repo'], "destroyer", 1),
        makePiece($app['piece-type-repo'], "groundforce", 1),
        makePiece($app['piece-type-repo'], "groundforce", 1),
        makePiece($app['piece-type-repo'], "groundforce", 1),
        makePiece($app['piece-type-repo'], "groundforce", 1),
        makePiece($app['piece-type-repo'], "groundforce", 1),
        makePiece($app['piece-type-repo'], "groundforce", 1),
        makePiece($app['piece-type-repo'], "groundforce", 2),
        makePiece($app['piece-type-repo'], "groundforce", 2),
        makePiece($app['piece-type-repo'], "groundforce", 2),
		makePiece($app['piece-type-repo'], "Planet", 2),
    ];

    $log = new \Plu\Service\Loggers\GroundBattleLog($tile);

    $report = $battleService->resolve($pieces, $log);

    print_r($report);
    exit;
});