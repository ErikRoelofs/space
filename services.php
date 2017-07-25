<?php

use \Plu\Converters as Conv;
use \Plu\Repository as Repo;

$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'dbs.options' => array (
        'mysql_read' => array(
            'driver'    => 'pdo_mysql',
            'host'      => 'db',
            'dbname'    => 'games',
            'user'      => 'root',
            'password'  => 'derp',
            'charset'   => 'utf8mb4',
        )
    )
));

$app['converter-service'] = function($app) {
    $s = new \Plu\Service\ConverterService();

    $s->addConverter('\Plu\Entity\Game', new Conv\GameConverter($app));
    $s->addConverter('\Plu\Entity\GivenOrder', new Conv\OrderConverter($app));
    $s->addConverter('\Plu\Entity\Piece', new Conv\ConfigurableConverter([
        'id' => new Conv\NativeConverter(),
        'typeId' => new Conv\NativeConverter(),
        'ownerId' => new Conv\NativeConverter(),
        'turnId' => new Conv\NativeConverter(),
        'tileId' => new Conv\NativeConverter(),
		'traits' => new Conv\TraitConverter(),
    ]));
    $s->addConverter('\Plu\Entity\PieceType', new Conv\ConfigurableConverter([
        'id' => new Conv\NativeConverter(),
        'name' => new Conv\NativeConverter(),
        'traits' => new Conv\TraitConverter(),
    ]));
    $s->addConverter('\Plu\Entity\Player', new Conv\ConfigurableConverter([
        'id' => new Conv\NativeConverter(),
        'gameId' => new Conv\NativeConverter(),
        'name' => new Conv\NativeConverter(),
        'color' => new Conv\NativeConverter(),
    ]));
    $s->addConverter('\Plu\Entity\Tile', new Conv\TileConverter($app));
    $s->addConverter('\Plu\Entity\Turn', new Conv\TurnConverter($app));

    $s->addConverter('\Plu\Entity\Log', new Conv\ConfigurableConverter([
        'id' => new Conv\NativeConverter(),
        'turnId' => new Conv\NativeConverter(),
        'service' => new Conv\NativeConverter(),
        'results' => new Conv\DataConverter(),
        'origin' => new Conv\NativeConverter(),
        'originId' => new Conv\NativeConverter(),
    ]));
	$s->addConverter('\Plu\Entity\ResourceClaim', new Conv\ConfigurableConverter([
		'id' => new Conv\NativeConverter(),
		'turnId' => new Conv\NativeConverter(),
		'playerId' => new Conv\NativeConverter(),
		'resource' => new Conv\NativeConverter(),
		'amount' => new Conv\NativeConverter(),
	]));

    return $s;
};

$app['temp-repo'] = function($app) {
    return new Repo\TempRepository($app['db'], $app['converter-service']);
};
$app['game-repo'] = function($app) {
    return new Repo\GameRepository($app['db'], $app['converter-service']);
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
$app['player-repo'] = function($app) {
    return new Repo\PlayerRepository($app['db'], $app['converter-service']);
};
$app['tile-repo'] = function($app) {
    return new Repo\TileRepository($app['db'], $app['converter-service']);
};
$app['turn-repo'] = function($app) {
    return new Repo\TurnRepository($app['db'], $app['converter-service']);
};

$app['log-repo'] = function($app) {
    return new Repo\LogRepository($app['db'], $app['converter-service']);
};
$app['resource-claim-repo'] = function($app) {
	return new Repo\ResourceClaimRepository($app['db'], $app['converter-service']);
};

$app['starting-units-service'] = function($app) {
    return new \Plu\Service\StartingUnitService($app['piece-type-repo']);
};

$app['new-board-service'] = function($app) {
    return new \Plu\Service\NewBoardService($app['new-planet-service']);
};

$app['new-planet-service'] = function($app) {
    return new \Plu\Service\NewPlanetService($app['piece-type-repo']);
};

$app['new-game-service'] = function($app) {
    return new \Plu\Service\NewGameService($app);
};

$app['orders-service'] = function($app) {
    return new \Plu\Service\OrdersService($app['order-repo'], $app['turn-repo'], $app['game-repo']);
};

$app['piece-service'] = function($app) {
    return new \Plu\Service\PieceService($app['piece-type-repo'], $app['piece-repo']);
};

$app['order-service'] = function($app) {
    $s = new \Plu\Service\OrderService($app['order-repo'], $app['game-service']);
    $s->addOrderType( new \Plu\OrderTypes\TacticalOrder($app['order-repo'], $app['orders-service'], $app['piece-repo'], $app['piece-type-repo'], $app['piece-service'], $app['pathfinding-service'], $app['tile-repo'], $app['resource-service']));

    return $s;
};

$app['tactical-order-service'] = function($app) {
    return $app['order-service']->getOrder(\Plu\OrderTypes\TacticalOrder::TAG);
};

$app['pathfinding-service'] = function($app) {
    return new \Plu\Service\PathfindingService($app['piece-service']);
};

$app['space-battle-service'] = function($app) {
    return new \Plu\Service\SpaceBattleService($app['piece-service']);
};

$app['invasion-battle-service'] = function($app) {
    return new \Plu\Service\GroundBattleService($app['piece-service']);
};

$app['combat-phase-service'] = function($app) {
    return new \Plu\TurnPhase\CombatPhaseService($app['space-battle-service'], $app['piece-service']);
};
$app['invasion-phase-service'] = function($app) {
    return new \Plu\TurnPhase\InvasionPhaseService($app['invasion-battle-service'], $app['piece-service']);
};

$app['piece-types-service'] = function($app) {
    return new \Plu\Service\PieceTypesService();
};

$app['game-service'] = function($app) {
    return new \Plu\Service\GameService($app['game-repo'], $app['tile-repo'], $app['piece-repo'], $app['player-repo'], $app['piece-type-repo'], $app['order-repo'], $app['turn-repo'], $app['log-repo']);
};

$app['end-of-turn-service'] = function($app) {
    return new \Plu\Service\EndOfTurnService($app['order-service'], $app['player-repo'], $app['combat-phase-service'], $app['invasion-phase-service'], $app['turn-repo'], $app['log-repo'], $app);
};

$app['resource-service'] = function($app) {
	return new \Plu\Service\ResourceService($app['piece-service'], $app['game-service'], $app['resource-claim-repo']);
};

$app['space-combat-log-expander'] = function($app) {
    return new \Plu\Service\LogExpanders\SpaceCombatLogExpander(
        $app['game-service'],
        $app['converter-service'],
        $app['turn-repo'],
        $app['piece-repo']
    );
};

$app['tactical-order-log-expander'] = function($app) {
    return new \Plu\Service\LogExpanders\TacticalOrderLogExpander($app['piece-repo']);
};

$app['log-expander'] = function($app) {
    $exp = new \Plu\Service\LogExpanderService();
    $exp->addExpander('space-battle-service', $app['space-combat-log-expander']);
    $exp->addExpander('tactical-order-service', $app['tactical-order-log-expander']);
    return $exp;
};
