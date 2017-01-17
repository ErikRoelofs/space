<?php

namespace Plu\Service;

use Plu\PieceTrait\Bombs;
use Plu\PieceTrait\FightsGroundBattles;
use Plu\Repository\PlanetRepository;
use Plu\Service\Loggers\GroundBattleLog;

class GroundBattleService {

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

	/**
	 * @var PlanetRepository
	 */
	private $planetRepo;

	private $piecesPerPlayer = [];

	private $round = 0;
	private $phase;

	/**
	 * GroundBattleService constructor.
	 *
	 * @param \Plu\Repository\PieceRepository $pieceRepo
	 * @param \Plu\Service\PieceService $pieceService
	 * @param array $piecesPerPlayer
	 * @param int $round
	 */
	public function __construct(\Plu\Repository\PieceRepository $pieceRepo, \Plu\Service\PieceService $pieceService, PlanetRepository $planetRepo) {
		$this->pieceRepo = $pieceRepo;
		$this->pieceService = $pieceService;
		$this->historyLog = new GroundBattleLog();
		$this->planetRepo = $planetRepo;
	}

	public function resolveAllGroundBattles(Game $game) {
		$board = $this->boardRepository->findByGame($game);
		$tiles = $this->tileRepository->findByBoard($board);
		$logs = [];
		foreach($tiles as $tile) {
			foreach($this->planetRepo->findByTile($tile) as $planet) {
				if ($this->hasGroundbattle($planet)) {
					$logs[] = $this->resolveGroundBattle($tile, $planet);
				}
			}
		}
		return $logs;
	}

	private function hasGroundbattle(Planet $planet) {
		$piecesPerPlayer = $this->collectPieces($planet);
		// more than one player on a planet == conflict
		return count($piecesPerPlayer) > 1;
	}

	public function resolveGroundBattle(Tile $tile, Planet $planet) {
		$this->piecesPerPlayer = $this->collectPieces($tile, $planet);

		// drop bombs
		$this->phase = 'bombs';
		$this->handleBombs();

		// then main battle
		$this->phase = 'main';
		$this->handleMainCombat();

		// check for planet ownership
		$this->resolvePlanetOwner();

		return $this->historyLog;

	}

	private function collectPieces(Tile $tile, Planet $planet) {
		$pieces = $this->pieceService->findByTile($tile);
		$out = [];
		foreach($pieces as $piece) {
			// only the ones that fight on the ground and are on this planet
			if(!($piece->location['type'] == 'planet' && $piece->location['id']  == $planet->id)) {
				continue;
			}
			if(!$this->pieceService->hasTrait($piece, FightsGroundBattles::TAG)) {
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

	private function handleBombs(Tile $tile) {
		$pieces = $this->pieceService->findByTile($tile);
		$bombers = [];
		foreach($pieces as $piece) {
			// only get pieces that can drop bombs
			if(!$this->pieceService->hasTrait($piece, Bombs::TAG)) {
				continue;
			}
			$bombers [] = $piece;
		}
		foreach($bombers as $bomber) {
			$stats = $this->pieceService->getTraitContents($bomber, Bombs::TAG);
			for($i = 0; $i<$stats['shots']; $i++) {
				if(mt_rand(0, 100) <= $stats['firepower']) {
					$this->resolveHit($bomber->ownerId, 'bomb');
				}
			}
		}
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
			$stats = $this->pieceService->getTraitContents($piece, FightsGroundBattles::TAG);
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

	/**
	 * If no pieces remain, owner does not change.
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