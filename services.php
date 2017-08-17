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

$app->register(new Silex\Provider\SecurityServiceProvider());

$app['users'] = function ($app) {
    return new \Plu\User\UserProvider($app['user-repo']);
};

$app['security.jwt'] = [
    'secret_key' => 'LookIMadeAKey!',
    'life_time'  => 86400,
    'options'    => [
        'username_claim' => 'sub',
        'header_name' => 'X-Access-Token',
        'token_prefix' => 'Bearer',
    ]
];
$app->register(new Silex\Provider\SecurityJWTServiceProvider());

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
        'userId' => new Conv\NativeConverter(),
        'ready' => new Conv\NativeConverter(),
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
		'ownerId' => new Conv\NativeConverter(),
		'orderId' => new Conv\NativeConverter(),
		'resource' => new Conv\NativeConverter(),
		'amount' => new Conv\NativeConverter(),
	]));
	$s->addConverter('\Plu\Entity\ActiveObjective', new Conv\ConfigurableConverter([
	    'id' => new Conv\NativeConverter(),
	    'gameId' => new Conv\NativeConverter(),
	    'turnId' => new Conv\NativeConverter(),
	    'value' => new Conv\NativeConverter(),
	    'type' => new Conv\NativeConverter(),
	    'params' => new Conv\DataConverter(),
    ]));
	$s->addConverter('\Plu\Entity\ClaimedObjective', new Conv\ConfigurableConverter([
	    'id' => new Conv\NativeConverter(),
	    'playerId' => new Conv\NativeConverter(),
	    'turnId' => new Conv\NativeConverter(),
	    'objectiveId' => new Conv\NativeConverter(),
    ]));
    $s->addConverter('\Plu\Entity\OpenGame', new Conv\OpenGameConverter($app));
    $s->addConverter('\Plu\Entity\SubscribedPlayer', new Conv\ConfigurableConverter([
        'id' => new Conv\NativeConverter(),
        'openGameId' => new Conv\NativeConverter(),
        'userId' => new Conv\NativeConverter(),
        'name' => new Conv\NativeConverter(),
    ]));

    $s->addConverter('\Plu\Entity\User', new Conv\ConfigurableConverter([
        'id' => new Conv\NativeConverter(),
        'name' => new Conv\NativeConverter(),
        'password' => new Conv\PasswordConverter(),
    ]));

    return $s;
};

$app['temp-repo'] = function($app) {
    return new Repo\TempRepository($app['db'], $app['converter-service']);
};
$app['game-repo'] = function($app) {
    return new Repo\GameRepository($app['db'], $app['converter-service']);
};
$app['open-game-repo'] = function($app) {
    return new Repo\OpenGameRepository($app['db'], $app['converter-service']);
};
$app['order-repo'] = function($app) {
    return new Repo\OrderRepository($app['db'], $app['resource-claim-repo'], $app['converter-service']);
};
$app['piece-repo'] = function($app) {
    return new Repo\PieceRepository($app['db'], $app['converter-service']);
};
$app['piece-type-repo'] = function($app) {
    return new Repo\PieceTypeRepository($app['db'], $app['converter-service']);
};
$app['player-repo'] = function($app) {
    return new Repo\PlayerRepository($app['db'], $app['converter-service'], $app['user']);
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
$app['user-repo'] = function($app) {
    return new Repo\UserRepository($app['db'], $app['converter-service']);
};
$app['resource-claim-repo'] = function($app) {
	return new Repo\ResourceClaimRepository($app['db'], $app['converter-service']);
};
$app['claimed-objective-repo'] = function($app) {
    return new Repo\ClaimedObjectiveRepository($app['db'], $app['converter-service']);
};
$app['active-objective-repo'] = function($app) {
    return new Repo\ActiveObjectiveRepository($app['db'], $app['converter-service']);
};
$app['subscribed-player-repo'] = function($app) {
    return new Repo\SubscribedPlayerRepository($app['db'], $app['converter-service']);
};

$app['starting-units-service'] = function($app) {
    return new \Plu\Service\StartingUnitService($app['piece-type-repo']);
};

$app['new-board-service'] = function($app) {
    return new \Plu\Service\NewBoardService($app['static-board-from-file']);
};

$app['static-board-from-file'] = function($app) {
    return new \Plu\Board\StaticBoardFromFile('assets/boards/board.yaml');
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
    $s->addOrderType( new \Plu\OrderTypes\ClaimObjectiveOrder($app['objective-service'], $app['active-objective-repo']));

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
    return new \Plu\Service\GameService($app['game-repo'], $app['tile-repo'], $app['piece-repo'], $app['player-repo'], $app['piece-type-repo'], $app['order-repo'], $app['turn-repo'], $app['log-repo'], $app['active-objective-repo'], $app['claimed-objective-repo']);
};

$app['end-of-turn-service'] = function($app) {
    return new \Plu\Service\EndOfTurnService($app['order-service'], $app['player-repo'], $app['combat-phase-service'], $app['invasion-phase-service'], $app['turn-repo'], $app['log-repo'], $app['objective-service'], $app['game-repo'], $app);
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

$app['objective-service'] = function($app) {
    $s = new \Plu\Service\ObjectiveService($app['active-objective-repo'], $app['objective-creator']);
    $s->addObjectiveType(new \Plu\Objective\HasResourceObjective($app['resource-service'], $app['claimed-objective-repo']));
    $s->addObjectiveType(new \Plu\Objective\HasPiecesObjective($app['claimed-objective-repo']));

    return $s;
};

$app['objective-creator'] = function($app) {
    return new \Plu\Service\ObjectiveCreator();
};

$app['lobby-service'] = function($app) {
    return new \Plu\Service\LobbyService($app['user'], $app['subscribed-player-repo'], $app['open-game-repo'], $app['new-game-service']);
};

$app['player-service'] = function($app) {
    return new \Plu\Service\PlayerService($app['user'], $app['player-repo'], $app['end-of-turn-service'], $app['game-service']);
};

$app['security.firewalls'] = array(
    'login' => [
        'pattern' => 'login|register|oauth',
        'anonymous' => true,
    ],
    'secured' => array(
        'pattern' => '^.*$',
        'logout' => array('logout_path' => '/logout'),
        'users' => $app['users'],
        'stateless' => true,
        'jwt' => array(
            'use_forward' => true,
            'require_previous_session' => false,
            'stateless' => true,
        )
    ),
);
