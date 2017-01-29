<?php

namespace Plu\TurnPhase;

use Plu\Entity\Game;
use Plu\Entity\Piece;
use Plu\Entity\Tile;
use Plu\Entity\Turn;
use Plu\PieceTrait\Grounded;
use Plu\Repository\PieceRepository;
use Plu\Service\AbstractBattleService;
use Plu\Service\GroundBattleService;
use Plu\Service\PieceService;

abstract class AbstractBattlePhaseService
{

	/**
	 * @var AbstractBattleService
	 */
	protected $battleService;

	/**
	 * @var PieceRepository
	 */
	protected $pieceRepo;

	/**
	 * @var PieceService
	 */
	protected $pieceService;

	/**
	 * AbstractBattlePhaseService constructor.
	 *
	 * @param \Plu\Service\AbstractBattleService $battleService
	 * @param \Plu\Repository\PieceRepository $pieceRepo
	 * @param \Plu\Service\PieceService $pieceService
	 */
	public function __construct(\Plu\Service\AbstractBattleService $battleService, \Plu\Repository\PieceRepository $pieceRepo, \Plu\Service\PieceService $pieceService) {
		$this->battleService = $battleService;
		$this->pieceRepo = $pieceRepo;
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

	abstract protected function involved(Piece $piece);
	abstract protected function newLog(Tile $tile);

}