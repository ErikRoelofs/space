<?php

namespace Plu\OrderTypes;


use Plu\Entity\Game;
use Plu\Entity\GivenOrder;
use Plu\Entity\Player;
use Plu\Repository\ActiveObjectiveRepository;
use Plu\Service\ObjectiveService;

class ClaimObjectiveOrder implements OrderTypeInterface
{

    const TAG = 'order.claimObjective';

    /**
     * @var ObjectiveService
     */
    private $objectiveService;

    /**
     * @var ActiveObjectiveRepository
     */
    private $activeObjectiveRepo;

    public function getTag()
    {
        return self::TAG;
    }

    public function validateOrderAllowed(Player $player, Game $game, $data)
    {
        return true;
    }

    public function createOrder(Player $player, Game $game, $data)
    {
        $this->validateOrderAllowed($player, $game, $data);
        $order = new GivenOrder();
        $order->ownerId = $player->id;
        $order->turnId = $game->currentTurn()->id;
        $order->orderType = self::TAG;
        $order->data = $data;

        return $order;
    }

    public function resolveOrder(Player $player, Game $game, GivenOrder $order)
    {
        $activeObjective = $this->activeObjectiveRepo->findByIdentifier($order->data['objectiveId']);
        $objective = $this->objectiveService->createObjectiveFromActiveObjective($activeObjective);

        $objective->resolveClaim($game, $player, $activeObjective);
    }

}
