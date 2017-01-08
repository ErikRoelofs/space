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

    $s->addConverter('\Plu\Entity\Game', new Conv\ConfigurableConverter([
        'id' => new Conv\NativeConverter(),
    ]));
    $s->addConverter('\Plu\Entity\Board', new Conv\ConfigurableConverter([
        'id' => new Conv\NativeConverter(),
        'gameId' => new Conv\NativeConverter()
    ]));
    $s->addConverter('\Plu\Entity\GivenOrder', new Conv\ConfigurableConverter([
        'id' => new Conv\NativeConverter(),
        'ownerId' => new Conv\NativeConverter(),
        'turnId' => new Conv\NativeConverter(),
        'typeId' => new Conv\NativeConverter(),
        'data' => new Conv\DataConverter(),
    ]));
    $s->addConverter('\Plu\Entity\Piece', new Conv\ConfigurableConverter([
        'id' => new Conv\NativeConverter(),
        'location' => new Conv\DataConverter(),
        'typeId' => new Conv\NativeConverter(),
        'ownerId' => new Conv\NativeConverter(),
    ]));
    $s->addConverter('\Plu\Entity\PieceType', new Conv\ConfigurableConverter([
        'id' => new Conv\NativeConverter(),
        'allowedLocationTypes' => new Conv\DataConverter(),
        'attack' => new Conv\NativeConverter(),
        'defense' => new Conv\NativeConverter(),
        'speed' => new Conv\NativeConverter(),
        'traits' => new Conv\DataConverter(),
        'priority' => new Conv\NativeConverter(),
    ]));
    $s->addConverter('\Plu\Entity\Planet', new Conv\ConfigurableConverter([
        'id' => new Conv\NativeConverter(),
        'industry' => new Conv\NativeConverter(),
        'social' => new Conv\NativeConverter(),
        'ownerId' => new Conv\NativeConverter(),
        'tileId' => new Conv\NativeConverter(),
    ]));
    $s->addConverter('\Plu\Entity\Player', new Conv\ConfigurableConverter([
        'id' => new Conv\NativeConverter(),
        'industry' => new Conv\NativeConverter(),
        'social' => new Conv\NativeConverter(),
        'gameId' => new Conv\NativeConverter(),
    ]));
    $s->addConverter('\Plu\Entity\Resolution', new Conv\ConfigurableConverter([
        'id' => new Conv\NativeConverter(),
        'givenOrderId' => new Conv\NativeConverter(),
        'data' => new Conv\DataConverter(),
    ]));
    $s->addConverter('\Plu\Entity\Tile', new Conv\ConfigurableConverter([
        'id' => new Conv\NativeConverter(),
        'boardId' => new Conv\NativeConverter(),
        'location' => new Conv\DataConverter(),
    ]));
    $s->addConverter('\Plu\Entity\Turn', new Conv\ConfigurableConverter([
        'id' => new Conv\NativeConverter(),
        'gameId' => new Conv\NativeConverter(),
        'number' => new Conv\NativeConverter(),
    ]));


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
$app['resolution-repo'] = function($app) {
    return new Repo\ResolutionRepository($app['db'], $app['converter-service']);
};
$app['tile-repo'] = function($app) {
    return new Repo\TileRepository($app['db'], $app['converter-service']);
};
$app['turn-repo'] = function($app) {
    return new Repo\TurnRepository($app['db'], $app['converter-service']);
};