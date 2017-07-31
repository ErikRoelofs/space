<?php

namespace Plu\Service\Loggers;

use Plu\Entity\ActiveObjective;
use Plu\Entity\GivenOrder;
use Plu\Entity\Player;

class ClaimObjectiveLog implements LoggerInterface
{
    private $player;
    private $success;
    private $objective;

    /**
     * @var GivenOrder
     */
    private $order;

    /**
     * ClaimObjectiveLog constructor.
     * @param GivenOrder $order
     */
    public function __construct(GivenOrder $order)
    {
        $this->order = $order;
    }


    public function getService()
    {
        return 'objective-service';
    }

    public function setPlayer(Player $player) {
        $this->player = $player->id;
    }

    public function setSuccess($success) {
        $this->success = $success;
    }

    public function storeLog()
    {
        return [
            'success' => $this->success,
            'objective' => $this->objective,
            'player' => $this->player
        ];
    }

    public function getOrigin()
    {
        return 'order';
    }

    public function getOriginId()
    {
        return $this->order->id;
    }

    public function setActiveObjective(ActiveObjective $objective) {
        $this->objective = $objective->id;
    }

    public function getActiveObjectiveId() {
        return $this->objective;
    }

    public function getPlayerId() {
        return $this->player;
    }

    public function getSuccess() {
        return $this->success;
    }

}
