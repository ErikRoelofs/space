<?php

namespace Plu\OrderTypes;

use Plu\Entity\GivenOrder;
use Plu\Entity\Piece;
use Plu\Entity\Player;
use Plu\Entity\Tile;
use Plu\PieceTrait\Mobile;
use Plu\PieceTrait\Spaceborne;
use Plu\Repository\OrderRepository;
use Plu\Repository\PieceRepository;
use Plu\Service\OrdersService;
use Plu\Service\PathfindingService;
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
     * @var PathfindingService
     */
    protected $pathfindingService;

    /**
     * TacticalOrder constructor.
     * @param $orderRepo
     */
    public function __construct(OrderRepository $orderRepo, OrdersService $ordersService, PieceRepository $pieceRepo, PieceService $pieceService, PathfindingService $pathfindingService)
    {
        $this->orderRepo = $orderRepo;
        $this->ordersService = $ordersService;
        $this->pieceRepo = $pieceRepo;
        $this->pieceService = $pieceService;
        $this->pathfindingService = $pathfindingService;
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
		$tile = $this->tileRepository->findByIdentifier($order->data['tile']);
		foreach($order->data['pieces'] as $pieceId) {
			$piece = $this->pieceRepo->findByIdentifier($pieceId);
			// move it
			$piece->location = [ 'type' => 'space', 'coordinates' => $tile->coordinates ];
			$this->pieceRepo->update($piece);
		}

		// handle construction
    }

    public function getTag()
    {
        return $this->type;
    }

    public function getPotentialPiecesForOrder(Tile $tile, Player $player) {
        $pieces = $this->pieceRepo->findByPlayer($player);
        $potentials = [];
        foreach($pieces as $piece) {
            if($this->validatePiece($piece, $tile, $player)) {
                $potentials[] = $piece;
            }
        }
        return $potentials;
    }

    private function validatePiece(Piece $piece, Tile $tile, Player $player) {
        // belong to the player
        if($player->id != $piece->ownerId) {
            return false;
        }

        // spaceborne
        if(!$this->pieceService->hasTrait($piece, Spaceborne::TAG)) {
            return false;
        }
        // mobile
        if(!$this->pieceService->hasTrait($piece, Mobile::TAG)) {
            return false;
        }

        // has no orders
        if(!$this->validateNoOrdersSet($piece, $player)) {
            return false;
        }

        // in range
        if(!$this->pathfindingService->getInReach($piece, $tile)) {
            return false;
        }

        return true;
    }

    private function validateNoOrdersSet(Piece $piece, Player $player) {

        $otherOrders = $this->ordersService->getActiveOrdersForPlayer($player);
        // not have any other tactical orders set
        foreach($otherOrders as $order) {
            if($order->orderType == $this->type) {
                foreach($order->data['pieces'] as $otherPieceId) {
                    if($piece->id == $otherPieceId) {
                        return false;
                    }
                }
            }
        }
        return true;
    }


}