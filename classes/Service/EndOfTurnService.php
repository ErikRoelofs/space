<?php

namespace Plu\Service;

use Plu\Entity\Game;
use Plu\Entity\Log;
use Plu\Entity\Turn;
use Plu\Repository\LogRepository;
use Plu\Repository\PlayerRepository;
use Plu\Repository\TurnRepository;
use Plu\TurnPhase\CombatPhaseService;
use Plu\TurnPhase\InvasionPhaseService;

class EndOfTurnService {

	/**
	 * @var OrdersService
	 */
	private $ordersService;

	/**
	 * @var OrderService
	 */
	private $orderService;

	/**
	 * @var PlayerRepository
	 */
	private $playerRepo;

	/**
	 * @var CombatPhaseService
	 */
	private $combatPhaseService;

    /**
     * @var InvasionPhaseService
     */
    private $invasionPhaseService;

    /**
	 * @var TurnRepository;
	 */
	private $turnRepo;

    /**
     * @var LogRepository
     */
    private $logRepo;

    /**
     * EndOfRoundService constructor.
     * @param OrdersService $ordersService
     * @param OrderService $orderService
     * @param PlayerRepository $playerRepo
     * @param CombatPhaseService $combatPhaseService
     * @param InvasionPhaseService $invasionPhaseService
     * @param TurnRepository $turnRepo
     * @param LogRepository $logRepo
     */
    public function __construct(OrdersService $ordersService, OrderService $orderService, PlayerRepository $playerRepo, CombatPhaseService $combatPhaseService, InvasionPhaseService $invasionPhaseService, TurnRepository $turnRepo, LogRepository $logRepo)
    {
        $this->ordersService = $ordersService;
        $this->orderService = $orderService;
        $this->playerRepo = $playerRepo;
        $this->combatPhaseService = $combatPhaseService;
        $this->invasionPhaseService = $invasionPhaseService;
        $this->turnRepo = $turnRepo;
        $this->logRepo = $logRepo;
    }

    public function endRound(Game $game) {
		$logs = [];
		$currentTurn = $this->turnRepo->getCurrentForGame($game);

		// collect & run orders
		$orders = $this->ordersService->getActiveOrdersForGame($game);
		foreach($orders as $order) {
			$logs[] = $this->orderService->resolveOrder($this->playerRepo->findByIdentifier($order->ownerId), $order);
		}

		// run all space battles
        $logs = array_merge($logs, $this->combatPhaseService->resolveAllSpaceBattles($game));

        // run all invasions
        $logs = array_merge($logs, $this->invasionPhaseService->resolveAllGroundBattles($game));

		// save all the logs
        $logEntities = [];
        foreach($logs as $log) {
            $logEntity = new Log();
            $logEntity->class = $log->getClass();
            $logEntity->results = $log->compileLog();
            $logEntity->turnId = $currentTurn->id;
            $logEntity->origin = $log->getOrigin();
            $logEntity->originId = $log->getOriginId();
            $logEntities[] = $logEntity;
            $this->logRepo->add($logEntity);
        }

        // update the actual gamestate
        foreach($logEntities as $logEntity) {
            $this->updateGamestate($game, $logEntity);
        }

		// start the next turn
		$this->nextTurn($game);

	}

	private function updateGamestate(Game $game, Log $logEntity) {
        $this->app[$logEntity->service]->updateGamestate($game, $logEntity);
    }

	private function nextTurn(Game $game) {
		// current turn number
		$currentTurn = $this->turnRepo->getCurrentForGame($game);

		// create a new turn
		$turn = new Turn();
		$turn->gameId = $game->id;
		$turn->number = $currentTurn->number + 1;
		$this->turnRepo->add($turn);

	}

}