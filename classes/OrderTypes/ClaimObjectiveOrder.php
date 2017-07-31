<?php

namespace Plu\OrderTypes;


use Plu\Entity\Game;
use Plu\Entity\GivenOrder;
use Plu\Entity\Player;
use Plu\Repository\ActiveObjectiveRepository;
use Plu\Service\Loggers\ClaimObjectiveLog;
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

    /**
     * ClaimObjectiveOrder constructor.
     * @param ObjectiveService $objectiveService
     * @param ActiveObjectiveRepository $activeObjectiveRepo
     */
    public function __construct(ObjectiveService $objectiveService, ActiveObjectiveRepository $activeObjectiveRepo)
    {
        $this->objectiveService = $objectiveService;
        $this->activeObjectiveRepo = $activeObjectiveRepo;
    }

    public function getTag()
    {
        return self::TAG;
    }

    public function validateOrderAllowed(Player $player, Game $game, $data)
    {
        if(!isset($data['objectiveId'])) {
            throw new \Exception("Requires an objective ID!");
        }
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

        $success = $objective->validateClaim($game, $player, $activeObjective);
        $log = new ClaimObjectiveLog($order);
        $log->setPlayer($player);
        $log->setSuccess($success);
        $log->setActiveObjective($activeObjective);
        return $log;

    }

}
