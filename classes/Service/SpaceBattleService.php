<?php

namespace Plu\Service;

use Plu\PieceTrait\FlakCannons;
use Plu\PieceTrait\MainCannon;
use Plu\PieceTrait\Torpedoes;
use Plu\Repository\PieceRepository;

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

    private $historyLog;

    private $piecesPerPlayer = [];

    public function resolveSpaceBattle(Tile $tile) {
        $this->piecesPerPlayer = $this->collectPieces($tile);

    }

    private function collectPieces(Tile $tile) {
        // collect anything spaceborne in this sector that participates in space battles

        // collect anything contained in an involved ship that participated in space battles

        // sort them out per player
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

    }

    private function handleMainRound() {
        $this->handleWeapon(MainCannon::TAG, 'normal');
    }

    private function resolveHit(Player $scoredBy, $type) {
        // pick a random target from any other player based on priority
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