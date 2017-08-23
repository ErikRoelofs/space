<?php

namespace Plu\Objective;


use Plu\Entity\ActiveObjective;
use Plu\Entity\Game;
use Plu\Entity\Player;
use Plu\Repository\ClaimedObjectiveRepository;
use Plu\Service\PieceService;
use Plu\Service\ResourceService;

class ControlsCenterObjective extends AbstractObjective
{

    const TYPE = 'has.center';

    public function __construct(ClaimedObjectiveRepository $claimsRepository)
    {
        $this->claimsRepository = $claimsRepository;
    }

    function updateGamestate(Game $game, Player $player, ActiveObjective $objective)
    {
        // we only need the player to HAVE the pieces, so no updates
        return true;
    }

    public function validateClaim(Game $game, Player $player, ActiveObjective $objective)
    {
        $tile = $game->currentTurn()->getTileByCoords(3,3);
        foreach($tile->pieces as $piece) {
            if($piece->typeId == 1) { // should come from repo
                return true;
            }
        }
        return false;
    }

    public function getType()
    {
        return self::TYPE;
    }

}
