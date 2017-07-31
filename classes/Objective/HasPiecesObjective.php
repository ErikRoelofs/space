<?php

namespace Plu\Objective;


use Plu\Entity\ActiveObjective;
use Plu\Entity\Game;
use Plu\Entity\Player;
use Plu\Repository\ClaimedObjectiveRepository;
use Plu\Service\PieceService;
use Plu\Service\ResourceService;

class HasPiecesObjective extends AbstractObjective
{

    const TYPE = 'has.pieces';

    /**
     * HasResourceObjective constructor.
     * @param ResourceService $resourceService
     */
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
        $amount = $objective->getParam('amount');
        $type = $objective->getParam('type');
        $count = 0;
        foreach($game->findCurrentPiecesForPlayer($player) as $piece) {
            if($piece->typeId == $type) {
                $count++;
            }
        }
        return $count >= $amount;
    }

    public function getType()
    {
        return self::TYPE;
    }

}
