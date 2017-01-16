<?php

namespace Plu\Service;

use Plu\Entity\Game;
use Plu\Entity\Turn;
use Plu\Repository\PlayerRepository;
use Plu\Repository\TurnRepository;

class EndOfRoundService {

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
	 * @var SpaceBattleService
	 */
	private $spaceBattleService;

	/**
	 * @var TurnRepository;
	 */
	private $turnRepo;

	/**
	 * @var ResourceService;
	 */
	private $resourcesService;

	public function endRound(Game $game) {
		$logs = [];

		// collect & run orders
		$orders = $this->ordersService->getActiveOrdersForGame($game);
		foreach($orders as $order) {
			$logs[] = $this->orderService->resolveOrder($this->playerRepo->findByIdentifier($order->ownerId), $order);
		}

		// run all battles
		$logs = array_merge($logs, $this->spaceBattleService->resolveAllSpaceBattles($game));

		// save all the logs

		// start the next turn
		$this->nextTurn($game);

		$this->recalculateResources($game);
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

	/**
	 * Should we do this? Or just do it on the fly?
	 */
	private function recalculateResources(Game $game) {
		foreach($this->playerRepo->findByGame($game) as $player) {
			$this->resourcesService->calculateStartingResourcesFor($player);
		}
	}

}