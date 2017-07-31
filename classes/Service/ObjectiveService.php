<?php

namespace Plu\Service;

use Plu\Entity\ActiveObjective;
use Plu\Entity\Game;
use Plu\Entity\GivenOrder;
use Plu\Entity\Player;
use Plu\Objective\ObjectiveInterface;
use Plu\OrderTypes\ClaimObjectiveOrder;
use Plu\Repository\ActiveObjectiveRepository;
use Plu\Service\Loggers\LoggerInterface;

class ObjectiveService
{

    private $objectives = [];

    /**
     * @var ActiveObjectiveRepository
     */
    private $activeObjectiveRepo;

    /**
     * @var ObjectiveCreator
     */
    private $objectiveCreator;

    /**
     * ObjectiveService constructor.
     * @param ActiveObjectiveRepository $activeObjectiveRepo
     * @param ObjectiveCreator $objectiveCreator
     */
    public function __construct(ActiveObjectiveRepository $activeObjectiveRepo, ObjectiveCreator $objectiveCreator)
    {
        $this->activeObjectiveRepo = $activeObjectiveRepo;
        $this->objectiveCreator = $objectiveCreator;
    }

    /**
     * Immediately creates a new objective
     *
     * @param Game $game
     */
    public function newObjective(Game $game) {
        $activeObjective = $this->objectiveCreator->newObjective($game, $this->objectives);
        $this->activeObjectiveRepo->add($activeObjective);
    }

    public function hasWinner(Game $game) {
        foreach($game->players as $player) {
            $score = $this->calculateScore($player, $game);
            if($score >= 3) {
                return true;
            }
        }
        return false;
    }

    private function calculateScore(Player $player, Game $game) {
        $score = 0;
        foreach($game->findClaimsByPlayer($player) as $claim) {
            $score += $game->findObjective($claim->objectiveId)->value;
        }
        return $score;
    }

    public function createObjectiveFromOrder(GivenOrder $order) {
        if($order->orderType !== ClaimObjectiveOrder::TAG) {
            throw new \Exception("Cannot create an objective from this order.");
        }
        $activeObjective = $this->activeObjectiveRepo->findByIdentifier($order->data['objectiveId']);
        return $this->createObjectiveFromActiveObjective($activeObjective);
    }

    public function createObjectiveFromActiveObjective(ActiveObjective $objective) {
        foreach($this->objectives as $objectiveType) {
            if($objectiveType->getType() == $objective->type) {
                return $objectiveType;
            }
        }
        throw new \Exception("Objective type not found.");
    }

    public function addObjectiveType(ObjectiveInterface $objective) {
        $this->objectives[] = $objective;
    }

    public function updateGameState(Game $game, LoggerInterface $log) {
        if($log->getSuccess()) {
            $activeObjective = $this->activeObjectiveRepo->findByIdentifier($log->getActiveObjectiveId());
            $objective = $this->createObjectiveFromActiveObjective($activeObjective);

            $claim = $objective->resolveClaim($game, $game->findPlayer($log->getPlayerId()), $activeObjective);
            $game->claimedObjectives[] = $claim;
        }
    }

}
