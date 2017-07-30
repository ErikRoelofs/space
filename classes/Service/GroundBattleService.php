<?php

namespace Plu\Service;

use Plu\Entity\Planet;
use Plu\Entity\Tile;
use Plu\PieceTrait\Artillery;
use Plu\PieceTrait\Bombs;
use Plu\PieceTrait\Capturable;
use Plu\PieceTrait\FightsGroundBattles;
use Plu\PieceTrait\GroundCannon;
use Plu\PieceTrait\Grounded;
use Plu\Service\Loggers\GroundBattleLog;

class GroundBattleService  extends AbstractBattleService {

	/**
	 * GroundBattleService constructor.
	 *
	 * @param \Plu\Service\PieceService $pieceService
	 */
	public function __construct(\Plu\Service\PieceService $pieceService) {
        parent::__construct($pieceService, FightsGroundBattles::TAG, Grounded::TAG, FightsGroundBattles::PRIORITY);
	}

	protected function resolve() {

		// drop bombs
		$this->phase = 'bombs';
		$this->handleBombs();

        // fire artillery
        $this->phase = 'artillery';
        $this->handleArtillery();

		// then main battle
		$this->phase = 'main';
		$this->handleMainCombat();

		// check for captures
		$this->resolveCaptures();

		return $this->historyLog;
	}

	private function handleBombs() {
		$this->handleWeapon(Bombs::TAG, 'normal');
	}

	private function handleArtillery() {
        $this->handleWeapon(Artillery::TAG, 'normal');
    }

	private function handleMainCombat() {
		while($this->fightContinues()) {
			$this->handleMainRound();
			$this->round++;
		}
	}

	private function handleMainRound() {
		$this->handleWeapon(GroundCannon::TAG, 'normal');
	}

}
