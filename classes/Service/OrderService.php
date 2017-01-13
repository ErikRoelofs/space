<?php

namespace Plu\Service;

use Plu\Entity\GivenOrder;
use Plu\Entity\Player;
use Plu\OrderTypes\OrderTypeInterface;
use Plu\Repository\OrderRepository;

class OrderService
{

    private $types = [];
    /**
     * @var OrderRepository
     */
    protected $orderRepo;

    public function __construct($orderRepo)
    {
        $this->orderRepo = $orderRepo;
    }

    public function addOrderType(OrderTypeInterface $type) {
        $this->types[$type->getTag()] = $type;
    }

    public function createOrder(Player $player, $type, $instructions) {
        if(!isset($this->types[$type])) {
            throw new \Exception("No known order type: " . $type);
        }
        $order = $this->types[$type]->createOrder($player, $instructions);
        $order->turnId = 1;
        $this->orderRepo->add($order);

    }

    public function revertOrder(Player $player, GivenOrder $order) {
        $this->orderRepo->remove($order);
        return;
    }

    public function getOrder($type) {
        return $this->types[$type];
    }
}