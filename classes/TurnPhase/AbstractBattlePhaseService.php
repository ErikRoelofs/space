<?php

namespace Plu\TurnPhase;

use Plu\Entity\Game;
use Plu\Entity\Piece;
use Plu\Entity\Tile;
use Plu\Entity\Turn;
use Plu\PieceTrait\Grounded;
use Plu\Repository\PieceRepository;
use Plu\Service\AbstractBattleService;
use Plu\Service\GamestateUpdate;
use Plu\Service\GroundBattleService;
use Plu\Service\PieceService;

abstract class AbstractBattlePhaseService
{

	/**
	 * @var AbstractBattleService
	 */
	protected $battleService;

	/**
	 * @var PieceService
	 */
	protected $pieceService;

	/**
	 * AbstractBattlePhaseService constructor.
	 *
	 * @param \Plu\Service\AbstractBattleService $battleService
	 * @param \Plu\Service\PieceService $pieceService
	 */
	public function __construct(\Plu\Service\AbstractBattleService $battleService, \Plu\Service\PieceService $pieceService) {
		$this->battleService = $battleService;
		$this->pieceService = $pieceService;
	}

	public function resolveAllBattles(Game $game) {
		$logs = [];
		$turn = $game->currentTurn();
		foreach($turn->tiles as $tile) {
			$pieces = $tile->pieces;
			if($this->hasBattle($pieces)) {
				$logs[] = $this->battleService->resolveBattle($pieces, $this->newLog($tile));
			}
		}
		return $logs;
	}

	/**
	 * A happens if there are more than 1 player's pieces on a tile that are involved in one
	 *
	 * @param array $pieces
	 * @return bool
	 */
	private function hasBattle(array $pieces) {
	    // false, because "null" is a valid owner id for neutral pieces
		$first = false;
		foreach($pieces as $piece) {
			if(!$this->involved($piece)) {
				continue;
			}
			if($first === false) {
				$first = $piece->ownerId;
			}
			if($piece->ownerId != $first) {
				return true;
			}
		}
		return false;
	}

	abstract protected function involved(Piece $piece);
	abstract protected function newLog(Tile $tile);

}
