<?php

namespace Plu\OrderTypes;

use Plu\Entity\GivenOrder;
use Plu\Entity\Piece;
use Plu\Entity\Player;
use Plu\Entity\Tile;
use Plu\PieceTrait\Cargo;
use Plu\PieceTrait\Mobile;
use Plu\PieceTrait\Spaceborne;
use Plu\Repository\OrderRepository;
use Plu\Repository\PieceRepository;

use Plu\Service\Loggers\TacticalOrderLog;
use Plu\Repository\TileRepository;
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
     * @var TileRepository
     */
    protected $tileRepo;

    /**
     * TacticalOrder constructor.
     * @param $orderRepo
     */
    public function __construct(OrderRepository $orderRepo, OrdersService $ordersService, PieceRepository $pieceRepo, PieceService $pieceService, PathfindingService $pathfindingService, TileRepository $tileRepo)
    {
        $this->orderRepo = $orderRepo;
        $this->ordersService = $ordersService;
        $this->pieceRepo = $pieceRepo;
        $this->pieceService = $pieceService;
        $this->pathfindingService = $pathfindingService;
        $this->tileRepo = $tileRepo;
    }

    public function validateOrderAllowed(Player $player, $data)
    {

        $tile = $this->tileRepo->findByIdentifier($data['tile']);

        // the targeted tile must
        // not have any other orders by this player
        $otherOrders = $this->ordersService->getActiveOrdersForPlayer($player);
        foreach($otherOrders as $order) {
            if($order->orderType == $this->type) {
                if($order->data['tile'] == $tile->id) {
                    throw new \Exception("A tactical order already exists for this sector.");
                }
            }
        }

        // all ships sent must
        foreach($data['pieces'] as $pieceId) {
            $piece = $this->pieceRepo->findByIdentifier($pieceId);
            if(!$this->validatePiece($piece, $tile, $player)) {
                throw new \Exception("A piece was sent that is not valid for this move");
            }
        }

		// the fleet must have room for all its cargo
		// @TODO

        // all items queued for construction must
        foreach($data['newPieces'] as $pieceTypeId) {
            $pieceType = $this->pieceTypeRepo->findByIdentifier($pieceTypeId);
            if(!$this->validateConstructionOrder($pieceType, $tile, $player )) {
                throw new \Exception("A constuction order that was sent is not valid");
            }
        }

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
		$log = new TacticalOrderLog();
		$log->addPlayer($player);

		$tile = $this->tileRepository->findByIdentifier($order->data['tile']);
		foreach($order->data['pieces'] as $pieceId) {
			$piece = $this->pieceRepo->findByIdentifier($pieceId);
			// log the move
			$log->addPieceMoved($piece->location, $piece);
			// move it
			$piece->location = [ 'type' => 'space', 'coordinates' => $tile->coordinates ];
			$this->pieceRepo->update($piece);
		}

		// handle construction

		return $log;
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
        // mobile or is cargo
        if(!$this->pieceService->hasTrait($piece, Mobile::TAG) || $this->pieceService->hasTrait($piece, Cargo::TAG)) {
            return false;
        }

        // has no orders
        if(!$this->validateNoOrdersSet($piece, $player)) {
            return false;
        }

        // in range (if not carried)
        if(!$this->pathfindingService->getInReach($piece, $tile) && !$this->pieceService->hasTrait($piece, Cargo::TAG) ) {
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

    private function validateConstructionOrder(PieceType $type, Tile $tile, Player $player) {
        // be buildable by the player in this location
        // be affordable

    }

}