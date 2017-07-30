<?php

namespace Plu\TurnPhase;

use Plu\Entity\Game;
use Plu\Entity\Piece;
use Plu\Entity\Tile;
use Plu\PieceTrait\Grounded;
use Plu\Service\Loggers\GroundBattleLog;
use Plu\Service\Loggers\LoggerInterface;

class InvasionPhaseService extends AbstractBattlePhaseService
{
	protected function newLog(Tile $tile) {
		return new GroundBattleLog($tile);
	}

	protected function involved(Piece $piece) {
		return $this->pieceService->hasTrait($piece, Grounded::TAG);
	}

}
