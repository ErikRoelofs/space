<?php

namespace Plu\TurnPhase;

use Plu\Entity\Game;
use Plu\Entity\Tile;
use Plu\Entity\Turn;
use Plu\PieceTrait\Spaceborne;
use Plu\Repository\PieceRepository;
use Plu\Service\Loggers\SpaceBattleLog;
use Plu\Service\PieceService;
use Plu\Service\SpaceBattleService;

class CombatPhaseService
{

    /**
     * @var SpaceBattleService
     */
    private $spaceBattleService;

	/**
	 * @var PieceRepository
	 */
	private $pieceRepo;

	/**
	 * @var PieceService
	 */
	private $pieceService;

	/**
	 * CombatPhaseService constructor.
	 *
	 * @param \Plu\TurnPhase\SpaceBattleService $spaceBattleService
	 * @param \Plu\Repository\PieceRepository $pieceRepo
	 * @param \Plu\Service\PieceService $pieceService
	 */
	public function __construct(SpaceBattleService $spaceBattleService, \Plu\Repository\PieceRepository $pieceRepo, \Plu\Service\PieceService $pieceService) {
		$this->spaceBattleService = $spaceBattleService;
		$this->pieceRepo = $pieceRepo;
		$this->pieceService = $pieceService;
	}

	public function resolveAllSpaceBattles(Game $game) {
        $logs = [];
		$turn = $game->currentTurn();
        foreach($game->board->tiles as $tile) {
			$pieces = $this->collectPieces($tile, $turn);
            if($this->hasSpacebattle($pieces)) {
                $logs[] = $this->spaceBattleService->resolveBattle($pieces, new SpaceBattleLog($tile));
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
    private function hasSpacebattle(array $pieces) {
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
		$this->pieceService->hasTrait($piece, Spaceborne::TAG);
	}

}