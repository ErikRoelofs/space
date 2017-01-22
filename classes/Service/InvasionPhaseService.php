<?php

namespace Plu\Service;

use Plu\Service\Loggers\SpaceBattleLog;

class InvasionPhaseService
{

    /**
     * @var GroundBattleService
     */
    private $groundBattleService;

    /**
     * CombatPhaseService constructor.
     * @param $spaceBattleService
     */
    public function __construct(GroundBattleService $groundBattleService)
    {
        $this->groundBattleService = $groundBattleService;
    }

    public function resolveAllGroundBattles(Game $game) {
        $logs = [];
        foreach($game->board->tiles as $tile) {
            if($this->hasGroundbattle($tile)) {
                $logs[] = $this->groundBattleService->resolveGroundBattle($tile, new GroundBattleLog($tile));
            }
        }
        return $logs;
    }

    private function hasGroundbattle(Tile $tile) {
        $piecesPerPlayer = $this->collectPieces($tile);
        // more than one player on a tile == conflict
        return count($piecesPerPlayer) > 1;
    }

}