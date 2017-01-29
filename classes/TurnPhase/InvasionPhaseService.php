<?php

namespace Plu\TurnPhase;

use Plu\Entity\Piece;
use Plu\Entity\Tile;
use Plu\PieceTrait\Grounded;
use Plu\Service\Loggers\GroundBattleLog;

class InvasionPhaseService extends AbstractBattlePhaseService
{
	protected function newLog(Tile $tile) {
		return new GroundBattleLog($tile);
	}

	protected function involved(Piece $piece) {
		$this->pieceService->hasTrait($piece, Grounded::TAG);
	}

}