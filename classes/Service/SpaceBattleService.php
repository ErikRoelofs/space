<?php

namespace Plu\Service;

use Plu\Entity\Piece;
use Plu\Entity\Planet;
use Plu\Entity\Tile;
use Plu\PieceTrait\Cargo;
use Plu\PieceTrait\FightsGroundBattles;
use Plu\PieceTrait\FightsSpaceBattles;
use Plu\PieceTrait\FlakCannons;
use Plu\PieceTrait\MainCannon;
use Plu\PieceTrait\Tiny;
use Plu\PieceTrait\Torpedoes;
use Plu\PieceTrait\Transports;
use Plu\Service\Loggers\SpaceBattleLog;

class SpaceBattleService
{

    /**
     * @var PieceService
     */
    private $pieceService;

	/**
	 * @var SpaceBattleLog
	 */
    private $historyLog;

    private $piecesPerPlayer = [];

	private $round = 0;
	private $phase;

	/**
	 * SpaceBattleService constructor.
	 *
	 * @param \Plu\Repository\PieceRepository $pieceRepo
	 * @param \Plu\Service\PieceService $pieceService
	 * @param array $piecesPerPlayer
	 * @param int $round
	 */
	public function __construct(\Plu\Service\PieceService $pieceService) {
		$this->pieceService = $pieceService;
	}

	public function resolveSpaceBattle(Tile $tile, SpaceBattleLog $log) {

	    $this->historyLog = $log;
        $this->piecesPerPlayer = $this->collectPieces($tile);

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
		$this->cleanupCargo($tile);

		return $this->historyLog;

    }

    private function collectPieces(Tile $tile) {
        $out = [];
		foreach($tile->pieces as $piece) {
			// only the ones that fight in space
			if(!$this->pieceService->hasTrait($piece, FightsSpaceBattles::TAG)) {
				continue;
			}
			// sort them out per player
			if(!isset($out[$piece->ownerId])) {
				$out[$piece->ownerId] = [];
			}
			$out[$piece->ownerId][] = $piece;
		}
		return $out;
    }

    private function handleWeapon($weaponTag, $hitType) {
        $withWeapon = $this->getPiecesWithTag($weaponTag);
        $hitsPerPlayer = [];
        foreach($this->piecesPerPlayer as $player => $pieces) {
            $hitsPerPlayer[$player] = 0;
        }
        foreach($withWeapon as $piece) {
            $stats = $this->pieceService->getTraitContents($piece, $weaponTag);
            for($i = 0; $i<$stats['shots']; $i++) {
                if(mt_rand(0, 10) <= $stats['firepower']) {
                    $hitsPerPlayer[$piece->ownerId]++;
                }
            }
        }
        foreach($hitsPerPlayer as $player => $hits) {
            for($i = 0; $i<$hits; $i++) {
                $this->resolveHit($player, $hitType);
            }
        }
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

    private function resolveHit($scoredBy, $type) {
        // pick a random target from any other player based on priority
		$possibleTargets = [];
		foreach($this->piecesPerPlayer as $player => $pieces) {
			if($player == $scoredBy) {
				continue;
			}
			$possibleTargets = array_merge($possibleTargets, $this->getLowestPriorityFrom($pieces));
		}
		$possibleTargets = $this->filterTargetsByWeaponType($possibleTargets, $type);
		if(count($possibleTargets) > 0) {
            shuffle($possibleTargets);
            $hit = array_pop($possibleTargets);
            $this->historyLog->logHit($this->phase, $this->round, $scoredBy, $hit);
		    $this->takeHit($hit);
        }
    }

	private function getLowestPriorityFrom(array $pieces) {
		$lowest = 100;
		$found = [];
		foreach($pieces as $piece) {
			$stats = $this->pieceService->getTraitContents($piece, FightsSpaceBattles::TAG);
			if($stats[FightsSpaceBattles::PRIORITY] == $lowest) {
				$found[] = $piece;
			}
			elseif($stats[FightsSpaceBattles::PRIORITY] < $lowest) {
				$found = [$piece];
				$lowest = $stats[FightsSpaceBattles::PRIORITY];
			}
		}
		return $found;
	}

	private function takeHit(Piece $hit) {
		foreach($this->piecesPerPlayer as $player => $pieces) {
			foreach($pieces as $key => $piece) {
				if($piece == $hit) {
					unset($this->piecesPerPlayer[$player][$key]);
					return;
				}
			}
		}
	}

	private function filterTargetsByWeaponType($pieces, $type) {
		if($type == 'flak') {
			$out = [];
			foreach($pieces as $piece) {
				if($this->pieceService->hasTrait($piece, Tiny::TAG)) {
					$out[] = $piece;
				}
			}
			return $out;
		}
		return $pieces;
	}

	private function fightContinues() {
		$num = 0;
		foreach($this->piecesPerPlayer as $pieces) {
			if(count($pieces) > 0) {
				$num++;
			}
		}
		return $num > 1 && $this->round < 100;
	}


    private function getPiecesWithTag($tag) {
        $out = [];
        foreach($this->piecesPerPlayer as $player => $pieces){
            foreach($pieces as $piece) {
                if($this->pieceService->hasTrait($piece, $tag)) {
                    $out[] = $piece;
                }
            }
        }
        return $out;
    }

	private function cleanupCargo(Tile $tile) {

	    foreach($this->piecesPerPlayer as $player => $pieces) {
	        // planets have unlimited cargo storage
	        if($player == $tile->planet->ownerId) {
	            continue;
            }
            $cargoAllowed = 0;
	        $cargoUsed = 0;
	        foreach($pieces as $piece) {
	            if($this->pieceService->hasTrait($piece, Cargo::TAG)) {
                    $cargoUsed++;
                }
                if($this->pieceService->hasTrait(Transports::TAG)) {
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