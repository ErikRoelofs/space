<?php

namespace Plu\OrderTypes;

use Plu\Entity\GivenOrder;
use Plu\Entity\Player;
use Plu\PieceTrait\Mobile;
use Plu\PieceTrait\Spaceborne;
use Plu\Repository\OrderRepository;
use Plu\Repository\PieceRepository;
use Plu\Service\OrdersService;
use Plu\Service\PieceService;

class TacticalOrder implements OrderTypeInterface
{

    private $type = 'tactical';

    /**
     * @var OrdersService
     */
    protected $ordersService;

    /**
     * @var OrderRepository
     */
    protected $orderRepo;

    /**
     * @var PieceRepository
     */
    protected $pieceRepo;

    /**
     * @var PieceService
     */
    protected $pieceService;

    /**
     * TacticalOrder constructor.
     * @param $orderRepo
     */
    public function __construct(OrderRepository $orderRepo, OrdersService $ordersService, PieceRepository $pieceRepo, PieceService $pieceService)
    {
        $this->orderRepo = $orderRepo;
        $this->ordersService = $ordersService;
        $this->pieceRepo = $pieceRepo;
        $this->pieceService = $pieceService;
    }


    public function validateOrderAllowed(Player $player, $data)
    {

        // the targeted tile must
        // not have any other orders by this player
        $otherOrders = $this->ordersService->getActiveOrdersForPlayer($player);
        foreach($otherOrders as $order) {
            if($order->orderType == $this->type) {
                if($order->data['tile'] == $data['tile']) {
                    throw new \Exception("A tactical order already exists for this sector.");
                }
            }
        }

        // all ships sent must
        foreach($data['pieces'] as $pieceId) {
            $piece = $this->pieceRepo->findByIdentifier($pieceId);

            // belong to the player
            if($player->id != $piece->ownerId) {
                throw new \Exception("Piece " . $piece->id . " does not belong to this player.");
            }
            // not have any other tactical orders set
            foreach($otherOrders as $order) {
                if($order->orderType == $this->type) {
                    foreach($order->data['pieces'] as $otherPieceId) {
                        if($piece->id == $otherPieceId) {
                            throw new \Exception("Piece " . $piece->id . " already has tactical orders.");
                        }
                    }
                }
            }
            // spaceborne
            if(!$this->pieceService->hasTrait($piece, Spaceborne::TAG)) {
                throw new \Exception("Piece " . $piece->id . " is not spaceborne.");
            }
            // mobile
            if(!$this->pieceService->hasTrait($piece, Mobile::TAG)) {
                throw new \Exception("Piece " . $piece->id . " is not mobile.");
            }

            // be in reach of the target
            // @TODO
        }

        // all items queued for construction must
            // be buildable by the player in this location
            // be affordable

    }

    public function createOrder(Player $player, $data)
    {
        $this->validateOrderAllowed($player, $data);
        $order = new GivenOrder();
        $order->ownerId = $player->id;
        $order->orderType = $this->type;
        $order->data = $data;

        return $order;
    }

    public function resolveOrder(Player $player, GivenOrder $order)
    {

    }

    public function getTag()
    {
        return $this->type;
    }


}