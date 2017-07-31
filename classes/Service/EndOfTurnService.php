<?php

namespace Plu\Service;

use Plu\Entity\Game;
use Plu\Entity\Log;
use Plu\Entity\Turn;
use Plu\Repository\GameRepository;
use Plu\Repository\LogRepository;
use Plu\Repository\PlayerRepository;
use Plu\Repository\TurnRepository;
use Plu\Service\Loggers\LoggerInterface;
use Plu\TurnPhase\CombatPhaseService;
use Plu\TurnPhase\InvasionPhaseService;

class EndOfTurnService {

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
     * @var ObjectiveService
     */
    private $objectiveService;

    /**
     * @var GameRepository
     */
    private $gameRepo;

    private $app;

    /**
     * EndOfTurnService constructor.
     * @param OrderService $orderService
     * @param PlayerRepository $playerRepo
     * @param CombatPhaseService $combatPhaseService
     * @param InvasionPhaseService $invasionPhaseService
     * @param TurnRepository $turnRepo
     * @param LogRepository $logRepo
     * @param ObjectiveService $objectiveCreationService
     * @param $app
     */
    public function __construct(OrderService $orderService, PlayerRepository $playerRepo, CombatPhaseService $combatPhaseService, InvasionPhaseService $invasionPhaseService, TurnRepository $turnRepo, LogRepository $logRepo, ObjectiveService $objectiveService, GameRepository $gameRepo, $app)
    {
        $this->orderService = $orderService;
        $this->playerRepo = $playerRepo;
        $this->combatPhaseService = $combatPhaseService;
        $this->invasionPhaseService = $invasionPhaseService;
        $this->turnRepo = $turnRepo;
        $this->logRepo = $logRepo;
        $this->objectiveService = $objectiveService;
        $this->gameRepo = $gameRepo;
        $this->app = $app;
    }

    public function endRound(Game $game) {

        if(!$game->active) {
            throw new \Exception("Cannot end round; game is not active.");
        }

		$logs = [];

        // generate a new turn & copy all the pieces
        $currentTurn = $this->nextTurn($game);

        // collect & run orders
		$orders = $game->currentOrders();
		foreach($orders as $order) {
			$logs[] = $this->orderService->resolveOrder($game->findPlayer($order->ownerId), $order);
		}
		$this->completePhase($game, $currentTurn, $logs);

		// run all space battles
        $logs = $this->combatPhaseService->resolveAllBattles($game);
        $this->completePhase($game, $currentTurn, $logs);

        // run all invasions
        $logs = $this->invasionPhaseService->resolveAllBattles($game);
        $this->completePhase($game, $currentTurn, $logs);

        // create new objective
        $this->objectiveService->newObjective($game);

        // pop the ids off the pieces and persist the new ones
        $pieceRepo = $this->app['piece-repo'];
        foreach($currentTurn->tiles as $tile) {
            foreach($tile->pieces as $piece) {
                $piece->id = null;
                $piece->turnId = $currentTurn->id;
                $pieceRepo->add($piece);
            }
        }

        // check if this game is now resolved
        if($this->objectiveService->hasWinner($game)) {
            $this->endGame($game);
        }
	}

	private function completePhase(Game $game, Turn $currentTurn, array $logs) {
        // save all the logs
        $logEntities = [];
        foreach($logs as $log) {
            $logEntity = new Log();
            $logEntity->service = $log->getService();
            $logEntity->results = $log->storeLog();
            $logEntity->turnId = $currentTurn->id;
            $logEntity->origin = $log->getOrigin();
            $logEntity->originId = $log->getOriginId();
            $logEntities[] = [$logEntity, $log];
            $this->logRepo->add($logEntity);
        }

        // update the actual gamestate
        foreach($logEntities as $item) {
            $this->updateGamestate($game, $item[0], $item[1]);
        }
    }

	private function updateGamestate(Game $game, Log $log, LoggerInterface $logEntity) {
        $this->app[$log->service]->updateGamestate($game, $logEntity);
    }

	private function nextTurn(Game $game) {
		// current turn
		$currentTurn = $game->currentTurn();

		// create a new turn
		$turn = new Turn();
		$turn->gameId = $game->id;
		$turn->number = $currentTurn->number + 1;
		$this->turnRepo->add($turn);

        $turn->tiles = $currentTurn->tiles;

		return $turn;
	}

	private function endGame(Game $game) {
        $game->active = 0;
        $this->gameRepo->update($game);
    }

}
