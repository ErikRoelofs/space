<?php

namespace Plu\Objective;


use Plu\Entity\ActiveObjective;
use Plu\Entity\ClaimedObjective;
use Plu\Entity\Game;
use Plu\Entity\Player;
use Plu\OrderTypes\ClaimObjectiveOrder;
use Plu\Repository\ClaimedObjectiveRepository;

abstract class AbstractObjective implements ObjectiveInterface
{

    /**
     * @var ClaimedObjectiveRepository
     */
    private $claimsRepository;

    /**
     * AbstractObjective constructor.
     * @param ClaimedObjectiveRepository $claimsRepository
     */
    public function __construct(ClaimedObjectiveRepository $claimsRepository)
    {
        $this->claimsRepository = $claimsRepository;
    }

    public function resolveClaim(Game $game, Player $player, ActiveObjective $objective)
    {
        if(!$this->validateClaim($game, $player, $objective)) {
            throw new \Exception("Trying to resolve an invalid claim.");
        }

        // create the required entities to log this objective for the player
        $claimed = new ClaimedObjective();
        $claimed->playerId = $player->id;
        $claimed->objectiveId = $objective->id;
        $claimed->turnId = $game->currentTurn()->id;

        $this->claimsRepository->add($claimed);

        // update the gamestate to reflect the results of this claim (if any)
        return $this->updateGamestate($game, $player, $objective);
    }

    abstract function updateGamestate(Game $game, Player $player, ActiveObjective $objective);

}
