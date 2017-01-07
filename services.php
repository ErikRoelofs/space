<?php

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
    return new \Plu\Service\ConverterService();
};

$app['temp-repo'] = function($app) {
    return new Plu\Repository\TempRepository($app['db'], $app['converter-service']);
};

$app['game-repo'] = function($app) {
    return new Plu\Repository\GameRepository($app['db'], $app['converter-service']);
};
$app['board-repo'] = function($app) {
    return new Plu\Repository\BoardRepository($app['db'], $app['converter-service']);
};
$app['order-repo'] = function($app) {
    return new Plu\Repository\OrderRepository($app['db'], $app['converter-service']);
};
$app['piece-repo'] = function($app) {
    return new Plu\Repository\PieceRepository($app['db'], $app['converter-service']);
};
$app['piece-type-repo'] = function($app) {
    return new Plu\Repository\PieceTypeRepository($app['db'], $app['converter-service']);
};
$app['planet-repo'] = function($app) {
    return new Plu\Repository\PlanetRepository($app['db'], $app['converter-service']);
};
$app['player-repo'] = function($app) {
    return new Plu\Repository\PlayerRepository($app['db'], $app['converter-service']);
};
$app['resolution-repo'] = function($app) {
    return new Plu\Repository\ResolutionRepository($app['db'], $app['converter-service']);
};
$app['tile-repo'] = function($app) {
    return new Plu\Repository\TileRepository($app['db'], $app['converter-service']);
};
$app['turn-repo'] = function($app) {
    return new Plu\Repository\TurnRepository($app['db'], $app['converter-service']);
};