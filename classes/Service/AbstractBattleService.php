<?php

namespace Plu\Service;

use Plu\Entity\Game;
use Plu\Entity\Piece;
use Plu\Entity\Tile;
use Plu\PieceTrait\Capturable;
use Plu\PieceTrait\Tiny;
use Plu\Service\Loggers\AbstractBattleLog;
use Plu\Service\Loggers\LoggerInterface;

abstract class AbstractBattleService
{

    /**
     * @var PieceService
     */
    protected $pieceService;

    protected $tag;
    protected $priority;

	protected $pieces;

    protected $piecesPerPlayer = [];

    /**
     * @var AbstractBattleLog
     */
    protected $historyLog;

    protected $round = 0;
    protected $phase;

    /**
     * AbstractBattleService constructor.
     * @param PieceService $pieceService
     * @param $tag
     * @param $priority
     */
    public function __construct(PieceService $pieceService, $tag, $priority)
    {
        $this->pieceService = $pieceService;
        $this->tag = $tag;
        $this->priority = $priority;

	}

	public function resolveBattle(array $pieces, AbstractBattleLog $log) {

		$this->historyLog = $log;
		$this->pieces = $pieces;
		$this->piecesPerPlayer = $this->collectPieces();

		return $this->resolve();
	}

	abstract protected function resolve();

    protected function collectPieces() {
        $out = [];
        foreach($this->pieces as $piece) {
            // only the ones that fight in this battle
            if(!$this->pieceService->hasTrait($piece, $this->tag)) {
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

    protected function handleWeapon($weaponTag, $hitType) {
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

    protected function resolveHit($scoredBy, $type) {
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

    protected function getLowestPriorityFrom(array $pieces) {
        $lowest = 100;
        $found = [];
        foreach($pieces as $piece) {
            $stats = $this->pieceService->getTraitContents($piece, $this->tag);
            if($stats[$this->priority] == $lowest) {
                $found[] = $piece;
            }
            elseif($stats[$this->priority] < $lowest) {
                $found = [$piece];
                $lowest = $stats[$this->priority];
            }
        }
        return $found;
    }

    protected function takeHit(Piece $hit) {
        $this->cleanFromPiecesList($hit);
        $this->cleanFromPiecesPerPlayer($hit);
    }

    private function cleanFromPiecesPerPlayer(Piece $hit) {
        foreach($this->piecesPerPlayer[$hit->ownerId] as $key => $piece) {
            if($piece == $hit) {
                unset($this->piecesPerPlayer[$hit->ownerId][$key]);
                return;
            }
        }
    }
    private function cleanFromPiecesList(Piece $hit) {
        foreach($this->pieces as $key => $piece) {
            if($piece == $hit) {
                unset($this->pieces[$key]);
                return;
            }
        }
    }

    protected function filterTargetsByWeaponType($pieces, $type) {
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

    protected function fightContinues() {
        $num = 0;
        foreach($this->piecesPerPlayer as $pieces) {
            if(count($pieces) > 0) {
                $num++;
            }
        }
        return $num > 1 && $this->round < 100;
    }

    protected function getPiecesWithTag($tag) {
        $out = [];
        foreach($this->pieces as $piece){
            if($this->pieceService->hasTrait($piece, $tag)) {
                $out[] = $piece;
            }
        }
        return $out;
    }


	/**
	 * Whichever player has pieces left, earns all the capturable items.
	 * If all players are destroyed, they do not change owner.
	 */
	protected function resolveCaptures() {
		$capturables = $this->getPiecesWithTag(Capturable::TAG);
		if(!count($capturables)){
			return;
		}
		foreach($this->piecesPerPlayer as $player => $pieces) {
			if(count($pieces) > 0) {
				foreach($capturables as $capture) {
				    // only capture things that carry the tag for this battle type
				    if($this->pieceService->hasTrait($capture, $this->tag)) {
                        $capture->ownerId = $player;
                        $this->historyLog->logPieceCaptured($capture, $player);
                    }
				}
			}
		}
	}

    public function updateGamestate(Game $game, LoggerInterface $log)
    {
        $tile = $game->findTile($log->getTile());

        foreach($log->getHits() as $hit) {
            foreach($tile->pieces as $key => $piece) {
                if($hit['target'] == $piece->id) {
                    unset($tile->pieces[$key]);
                    continue;
                }
            }
        }
        foreach($log->getLostCargo() as $lost) {
            foreach($tile->pieces as $key => $piece) {
                if($lost['cargo'] == $piece->id) {
                    unset($tile->pieces[$key]);
                    continue;
                }
            }
        }

        foreach($log->getCaptures() as $capture) {
            foreach($tile->pieces as $key => $piece) {
                if($capture['piece'] == $piece->id) {
                    $piece->ownerId = $capture['newOwner'];
                    continue;
                }
            }
        }

    }

}