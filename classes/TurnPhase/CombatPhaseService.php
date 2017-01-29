<?php

namespace Plu\TurnPhase;

use Plu\Entity\Game;
use Plu\Entity\Piece;
use Plu\Entity\Tile;
use Plu\PieceTrait\Spaceborne;
use Plu\Service\Loggers\LoggerInterface;
use Plu\Service\Loggers\SpaceBattleLog;

class CombatPhaseService extends AbstractBattlePhaseService
{
	protected function newLog(Tile $tile) {
		return new SpaceBattleLog($tile);
	}

	protected function involved(Piece $piece) {
		return $this->pieceService->hasTrait($piece, Spaceborne::TAG);
	}

}