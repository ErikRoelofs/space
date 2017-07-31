<?php

namespace Plu\Objective;


use Plu\Entity\ActiveObjective;
use Plu\Entity\Game;
use Plu\Entity\Player;
use Plu\Repository\ClaimedObjectiveRepository;
use Plu\Service\ResourceService;

class HasResourceObjective extends AbstractObjective
{

    const TYPE = 'has.resource';

    /**
     * @var ResourceService
     */
    private $resourceService;

    /**
     * HasResourceObjective constructor.
     * @param ResourceService $resourceService
     */
    public function __construct(ResourceService $resourceService, ClaimedObjectiveRepository $claimsRepository)
    {
        $this->resourceService = $resourceService;
        $this->claimsRepository = $claimsRepository;
    }


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
