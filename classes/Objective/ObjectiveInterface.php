<?php

namespace Plu\Objective;

use Plu\Entity\ActiveObjective;
use Plu\Entity\Game;
use Plu\Entity\Player;
use Plu\OrderTypes\ClaimObjectiveOrder;

interface ObjectiveInterface
{
    // validate that the player can complete this claim (but change nothing)
    public function validateClaim(Game $game, Player $player, ActiveObjective $objective);

    // resolve this claim by updating gamestate to reflect it being completed
    public function resolveClaim(Game $game, Player $player, ActiveObjective $objective);

    // get identifier type for this objective (used for storing them)
    public function getType();
}
