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

    /**
     * @var GameService
     */
    protected $gameService;

    public function __construct(OrderRepository $orderRepo, GameService $gameService)
    {
        $this->orderRepo = $orderRepo;
        $this->gameService =$gameService;
    }

    public function addOrderType(OrderTypeInterface $type) {
        $this->types[$type->getTag()] = $type;
    }

    public function createOrder(Player $player, $type, $instructions) {
        if(!isset($this->types[$type])) {
            throw new \Exception("No known order type: " . $type);
        }
        $game = $this->gameService->buildGame($player->gameId);
        if(!$game->active) {
            throw new \Exception("Cannot add a new order; game is not active");
        }
        $turn = $game->currentTurn();
        if($turn->endTime && new \DateTime() > $turn->endTime) {
            throw new \Exception("This turn is no longer active; cannot create orders");
        }
        $order = $this->types[$type]->createOrder($player, $game, $instructions);
        $this->orderRepo->add($order);
    }

    public function revertOrder(GivenOrder $order) {
        $this->orderRepo->remove($order);
        return;
    }

    public function getOrder($type) {
        return $this->types[$type];
    }

	public function resolveOrder(Player $player, GivenOrder $order) {
        $game = $this->gameService->buildGame($player->gameId);
		return $this->types[$order->orderType]->resolveOrder($player, $game, $order);
	}
}
