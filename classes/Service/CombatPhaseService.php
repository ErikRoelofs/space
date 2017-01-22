<?php

namespace Plu\Service;

use Plu\Service\Loggers\SpaceBattleLog;

class CombatPhaseService
{

    /**
     * @var SpaceBattleService
     */
    private $spaceBattleService;

    /**
     * CombatPhaseService constructor.
     * @param $spaceBattleService
     */
    public function __construct(SpaceBattleService $spaceBattleService)
    {
        $this->spaceBattleService = $spaceBattleService;
    }

    public function resolveAllSpaceBattles(Game $game) {
        $logs = [];
        foreach($game->board->tiles as $tile) {
            if($this->hasSpacebattle($tile)) {
                $logs[] = $this->spaceBattleService->resolveSpaceBattle($tile, new SpaceBattleLog());
            }
        }
        return $logs;
    }

    private function hasSpacebattle(Tile $tile) {
        $piecesPerPlayer = $this->collectPieces($tile);
        // more than one player on a tile == conflict
        return count($piecesPerPlayer) > 1;
    }

    public function handleCombatStep(Game $game) {
        $this->resolveAllSpaceBattles($game);
    }

}