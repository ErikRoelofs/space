<?php

namespace Plu\Service;

use Plu\Entity\Tile;
use Plu\PieceTrait\Cargo;
use Plu\PieceTrait\FightsSpaceBattles;
use Plu\PieceTrait\FlakCannons;
use Plu\PieceTrait\MainCannon;
use Plu\PieceTrait\Torpedoes;
use Plu\PieceTrait\Transports;
use Plu\Service\Loggers\SpaceBattleLog;

class SpaceBattleService extends AbstractBattleService
{

	/**
	 * SpaceBattleService constructor.
	 *
	 * @param \Plu\Repository\PieceRepository $pieceRepo
	 * @param \Plu\Service\PieceService $pieceService
	 * @param array $piecesPerPlayer
	 * @param int $round
	 */
	public function __construct(\Plu\Service\PieceService $pieceService) {
	    parent::__construct($pieceService, FightsSpaceBattles::TAG, FightsSpaceBattles::PRIORITY);
	}

	protected function resolve() {

		// flak first
		$this->phase = 'flak';
		$this->handleFlak();
		// torpedoes second
		$this->phase = 'torpedoes';
		$this->handleTorpedoes();
		// then main battle
		$this->phase = 'main';
		$this->handleMainCombat();

		// remove all cargo that no longer has transport capacity
		$this->cleanupCargo();

		// check for captures
		$this->resolveCaptures();

		return $this->historyLog;
    }

    private function handleFlak() {
        $this->handleWeapon(FlakCannons::TAG, 'flak');
    }

    private function handleTorpedoes() {
        $this->handleWeapon(Torpedoes::TAG, 'normal');
    }

    private function handleMainCombat() {
		while($this->fightContinues()) {
			$this->handleMainRound();
			$this->round++;
		}
    }

    private function handleMainRound() {
        $this->handleWeapon(MainCannon::TAG, 'normal');
    }

	private function cleanupCargo() {

	    foreach($this->piecesPerPlayer as $player => $pieces) {
            $cargoAllowed = 0;
	        $cargoUsed = 0;
	        foreach($pieces as $piece) {
	            if($this->pieceService->hasTrait($piece, Cargo::TAG)) {
                    $cargoUsed++;
                }
                if($this->pieceService->hasTrait($piece, Transports::TAG)) {
	                $cargoAllowed += $this->pieceService->getTraitContents($piece, Transports::TAG);
                }
            }
            if($cargoUsed > $cargoAllowed) {
	            $this->destroyCargoForPlayer($cargoUsed - $cargoAllowed, $player);
            }
        }
    }

    private function destroyCargoForPlayer($amount, $player) {
	    $pieces = $this->piecesPerPlayer[$player];
	    shuffle($pieces);
	    foreach($pieces as $piece) {
	        if($this->pieceService->hasTrait($piece, Cargo::TAG)) {
	            $this->historyLog->logLostCargo($player, $piece);
	            $amount--;
            }
            if($amount == 0) {
	            return;
            }
        }
    }



}