<?php

namespace Plu\Service;

use Plu\Entity\Planet;
use Plu\Entity\Tile;
use Plu\PieceTrait\Artillery;
use Plu\PieceTrait\Bombs;
use Plu\PieceTrait\FightsGroundBattles;
use Plu\PieceTrait\GroundCannon;
use Plu\Service\Loggers\GroundBattleLog;

class GroundBattleService  extends AbstractBattleService {

	/**
	 * GroundBattleService constructor.
	 *
	 * @param \Plu\Service\PieceService $pieceService
	 */
	public function __construct(\Plu\Service\PieceService $pieceService) {
        parent::__construct($pieceService, FightsGroundBattles::TAG, FightsGroundBattles::PRIORITY);
	}

	public function resolveGroundBattle(Tile $tile, GroundBattleLog $groundBattleLog) {

	    $this->tile = $tile;
	    $this->historyLog = $groundBattleLog;
		$this->piecesPerPlayer = $this->collectPieces($tile);

		// drop bombs
		$this->phase = 'bombs';
		$this->handleBombs();

        // fire artillery
        $this->phase = 'artillery';
        $this->handleArtillery();

		// then main battle
		$this->phase = 'main';
		$this->handleMainCombat();

		// check for planet ownership
		$this->resolvePlanetOwner($this->tile->planet);

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

	/**
	 * If no pieces remain, owner does not change (even if the old owner has no more troops)
	 */
	private function resolvePlanetOwner(Planet $planet) {
		foreach($this->piecesPerPlayer as $player => $pieces) {
			if(count($pieces) > 0) {
				$planet->ownerId = $player;
				$this->historyLog->logPlanetCaptured($planet, $player);
			}
		}
	}

}