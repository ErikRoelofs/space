<?php

namespace Plu\TurnPhase;

use Plu\Entity\Game;
use Plu\Entity\Tile;
use Plu\Entity\Turn;
use Plu\PieceTrait\Grounded;
use Plu\Repository\PieceRepository;
use Plu\Service\GroundBattleService;
use Plu\Service\PieceService;

class InvasionPhaseService
{

	/**
	 * @var GroundBattleService
	 */
	private $groundBattleService;

	/**
	 * @var PieceRepository
	 */
	private $pieceRepo;

	/**
	 * @var PieceService
	 */
	private $pieceService;

	/**
	 * InvasionPhaseService constructor.
	 *
	 * @param \Plu\TurnPhase\GroundBattleService $groundBattleService
	 * @param \Plu\Repository\PieceRepository $pieceRepo
	 * @param \Plu\Service\PieceService $pieceService
	 */
	public function __construct(GroundBattleService $groundBattleService, \Plu\Repository\PieceRepository $pieceRepo, \Plu\Service\PieceService $pieceService) {
		$this->groundBattleService = $groundBattleService;
		$this->pieceRepo = $pieceRepo;
		$this->pieceService = $pieceService;
	}

	public function resolveAllGroundBattles(Game $game) {
		$logs = [];
		$turn = $game->currentTurn();
		foreach($game->board->tiles as $tile) {
			$pieces = $this->collectPieces($tile, $turn);
			if($this->hasGroundbattle($pieces)) {
				$logs[] = $this->groundBattleService->resolveBattle($pieces, new GroundBattleLog($tile));
			}
		}
		return $logs;
	}

	/**
	 * A spacebattle happens if there are more than 1 player's pieces on a tile that are involved in one
	 *
	 * @param array $pieces
	 * @return bool
	 */
	private function hasGroundbattle(array $pieces) {
		$first = null;
		foreach($pieces as $piece) {
			if(!$this->involved($piece)) {
				continue;
			}
			if(!$first) {
				$first = $piece->ownerId;
			}
			if($first && $piece->ownerId != $first) {
				return true;
			}
		}
		return false;
	}

	private function collectPieces(Tile $tile, Turn $turn) {
		return $this->pieceRepo->findByTileAndTurn($tile, $turn);
	}

	private function involved(Piece $piece) {
		$this->pieceService->hasTrait($piece, Grounded::TAG);
	}

}