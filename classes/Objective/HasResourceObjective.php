<?php

namespace Plu\Objective;


use Plu\Entity\ActiveObjective;
use Plu\Entity\Game;
use Plu\Entity\Player;
use Plu\OrderTypes\ClaimObjectiveOrder;
use Plu\Service\ResourceService;

class HasResourceObjective extends AbstractObjective
{

    const TYPE = 'has.resource';

    /**
     * @var ResourceService
     */
    private $resourceService;

    function updateGamestate(Game $game, Player $player, ActiveObjective $objective)
    {
        // we don't need to actually change resources; the turn is ending anyway
        return true;
    }

    public function validateClaim(Game $game, Player $player, ActiveObjective $objective)
    {
        $amount = $objective->getParam('amount');
        $resource = $objective->getParam('resource');
        return $this->resourceService->hasResources($player, $resource, $amount);
    }

    public function getType()
    {
        return self::TYPE;
    }

}
