<?php

namespace Plu\Service;

use Plu\PieceTrait\FightsSpaceBattles;
use Plu\PieceTrait\FlakCannons;
use Plu\PieceTrait\MainCannon;
use Plu\PieceTrait\Tiny;
use Plu\PieceTrait\Torpedoes;
use Plu\Repository\PieceRepository;
use Plu\Service\Loggers\SpaceBattleLog;

class SpaceBattleService
{

    /**
     * @var PieceRepository
     */
    private $pieceRepo;

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
	public function __construct(\Plu\Repository\PieceRepository $pieceRepo, \Plu\Service\PieceService $pieceService) {
		$this->pieceRepo = $pieceRepo;
		$this->pieceService = $pieceService;
		$this->historyLog = new SpaceBattleLog();
	}

	public function resolveAllSpaceBattles(Game $game) {
		$board = $this->boardRepository->findByGame($game);
		$tiles = $this->tileRepository->findByBoard($board);
		$logs = [];
		foreach($tiles as $tile) {
			if($this->hasSpacebattle($tile)) {
				$logs[] = $this->resolveSpaceBattle($tile);
			}
		}
		return $logs;
	}

	private function hasSpacebattle(Tile $tile) {
		$piecesPerPlayer = $this->collectPieces($tile);
		// more than one player on a tile == conflict
		return count($piecesPerPlayer) > 1;
	}

	public function resolveSpaceBattle(Tile $tile) {
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

		return $this->historyLog;

    }

    private function collectPieces(Tile $tile) {
        $pieces = $this->pieceService->findByTile($tile);
		$out = [];
		foreach($pieces as $piece) {
			// only the ones that fight in space
			if(!$this->pieceService->hasTrait($piece, FightsSpaceBattles::TAG)) {
				continue;
			}
			// sort them out per player
			if(!$out[$piece->ownerId]) {
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
                if(mt_rand(0, 100) <= $stats['firepower']) {
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

    private function resolveHit(Player $scoredBy, $type) {
        // pick a random target from any other player based on priority
		$possibleTargets = [];
		foreach($this->piecesPerPlayer as $player => $pieces) {
			if($player == $scoredBy->id) {
				continue;
			}
			$possibleTargets = array_merge($possibleTargets, $this->getLowestPriorityFrom($pieces));
		}
		$possibleTargets = $this->filterTargetsByWeaponType($possibleTargets, $type);

		shuffle($possibleTargets);
		$hit = array_pop($possibleTargets);
		$this->historyLog->logHit($this->phase, $this->round, $scoredBy, $hit);
		$this->takeHit($hit);
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
				}
			}
		}
	}

	private function filterTargetsByWeaponType($pieces, $type) {
		if($type == 'flak') {
			$out = [];
			foreach($pieces as $piece) {
				if($this->pieceService->hasTrait(Tiny::TAG)) {
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


}