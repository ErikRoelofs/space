<?php

use \Plu\Converters as Conv;
use \Plu\Repository as Repo;

$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'dbs.options' => array (
        'mysql_read' => array(
            'driver'    => 'pdo_mysql',
            'host'      => 'localhost',
            'dbname'    => 'games',
            'user'      => 'root',
            'password'  => null,
            'charset'   => 'utf8mb4',
        )
    )
));

$app['converter-service'] = function($app) {
    $s = new \Plu\Service\ConverterService();

    $s->addConverter('\Plu\Entity\Game', new Conv\GameConverter($app));
    $s->addConverter('\Plu\Entity\Board', new Conv\BoardConverter($app));
    $s->addConverter('\Plu\Entity\GivenOrder', new Conv\OrderConverter($app));
    $s->addConverter('\Plu\Entity\Piece', new Conv\ConfigurableConverter([
        'id' => new Conv\NativeConverter(),
        'location' => new Conv\DataConverter(),
        'typeId' => new Conv\NativeConverter(),
        'ownerId' => new Conv\NativeConverter(),
        'boardId' => new Conv\NativeConverter(),
    ]));
    $s->addConverter('\Plu\Entity\PieceType', new Conv\ConfigurableConverter([
        'id' => new Conv\NativeConverter(),
        'name' => new Conv\NativeConverter(),
        'traits' => new Conv\TraitConverter(),
    ]));
    $s->addConverter('\Plu\Entity\Planet', new Conv\ConfigurableConverter([
        'id' => new Conv\NativeConverter(),
        'industrial' => new Conv\NativeConverter(),
        'social' => new Conv\NativeConverter(),
        'ownerId' => new Conv\NativeConverter(),
        'tileId' => new Conv\NativeConverter(),
    ]));
    $s->addConverter('\Plu\Entity\Player', new Conv\ConfigurableConverter([
        'id' => new Conv\NativeConverter(),
        'industry' => new Conv\NativeConverter(),
        'social' => new Conv\NativeConverter(),
        'gameId' => new Conv\NativeConverter(),
        'name' => new Conv\NativeConverter(),
        'color' => new Conv\NativeConverter(),
    ]));
    $s->addConverter('\Plu\Entity\Tile', new Conv\TileConverter($app));
    $s->addConverter('\Plu\Entity\Turn', new Conv\TurnConverter($app));


    return $s;
};

$app['temp-repo'] = function($app) {
    return new Repo\TempRepository($app['db'], $app['converter-service']);
};
$app['game-repo'] = function($app) {
    return new Repo\GameRepository($app['db'], $app['converter-service']);
};
$app['board-repo'] = function($app) {
    return new Repo\BoardRepository($app['db'], $app['converter-service']);
};
$app['order-repo'] = function($app) {
    return new Repo\OrderRepository($app['db'], $app['converter-service']);
};
$app['piece-repo'] = function($app) {
    return new Repo\PieceRepository($app['db'], $app['converter-service']);
};
$app['piece-type-repo'] = function($app) {
    return new Repo\PieceTypeRepository($app['db'], $app['converter-service']);
};
$app['planet-repo'] = function($app) {
    return new Repo\PlanetRepository($app['db'], $app['converter-service']);
};
$app['player-repo'] = function($app) {
    return new Repo\PlayerRepository($app['db'], $app['converter-service']);
};
$app['tile-repo'] = function($app) {
    return new Repo\TileRepository($app['db'], $app['converter-service']);
};
$app['turn-repo'] = function($app) {
    return new Repo\TurnRepository($app['db'], $app['converter-service']);
};

$app['starting-units-service'] = function($app) {
    return new \Plu\Service\StartingUnitService($app['piece-type-repo']);
};

$app['new-board-service'] = function($app) {
    return new \Plu\Service\NewBoardService($app['new-planet-service']);
};

$app['new-planet-service'] = function($app) {
    return new \Plu\Service\NewPlanetService();
};

$app['new-game-service'] = function($app) {
    return new \Plu\Service\NewGameService($app);
};

$app['orders-service'] = function($app) {
    return new \Plu\Service\OrdersService($app['order-repo'], $app['turn-repo'], $app['game-repo']);
};

$app['piece-service'] = function($app) {
    return new \Plu\Service\PieceService($app['piece-type-repo'], $app['piece-repo'], $app['planet-repo'], $app['board-repo']);
};

$app['order-service'] = function($app) {
    $s = new \Plu\Service\OrderService($app['order-repo']);
    $s->addOrderType( new \Plu\OrderTypes\TacticalOrder($app['order-repo'], $app['orders-service'], $app['piece-repo'], $app['piece-service'], $app['pathfinding-service']));

    return $s;
};

$app['pathfinding-service'] = function($app) {
    return new \Plu\Service\PathfindingService($app['piece-service']);
};

$app['space-battle-service'] = function($app) {
    return new \Plu\Service\SpaceBattleService($app['piece-repo'], $app['piece-service'], $app['planet-repo'], $app['board-repo'], $app['tile-repo']);
};

$app['piece-types-service'] = function($app) {
    return new \Plu\Service\PieceTypesService();
};