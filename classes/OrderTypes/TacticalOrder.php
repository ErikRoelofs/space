<?php

namespace Plu\OrderTypes;

use Plu\Entity\Game;
use Plu\Entity\GivenOrder;
use Plu\Entity\Piece;
use Plu\Entity\PieceType;
use Plu\Entity\Player;
use Plu\Entity\Tile;
use Plu\PieceTrait\Cargo;
use Plu\PieceTrait\Mobile;
use Plu\PieceTrait\Spaceborne;
use Plu\Repository\OrderRepository;
use Plu\Repository\PieceRepository;

use Plu\Service\GamestateUpdate;
use Plu\Service\Loggers\LoggerInterface;
use Plu\Service\Loggers\TacticalOrderLog;
use Plu\Repository\TileRepository;
use Plu\Service\OrdersService;
use Plu\Service\PathfindingService;
use Plu\Service\PieceService;

class TacticalOrder implements OrderTypeInterface, GamestateUpdate
{

    const TAG = 'tactical';

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

    public function validateOrderAllowed(Player $player, Game $game, $data)
    {

        $turn = $game->currentTurn();
        $tile = $game->findTile($data['tile']);
        if(!$tile) {
            throw new \Exception("This tile is not a part of this game.");
        }

        // not have any other orders by this player
        $otherOrders = $game->currentOrdersForPlayer($player);
        foreach($otherOrders as $order) {
            if($order->orderType == self::TAG) {
                if($order->data['tile'] == $tile->id) {
                    throw new \Exception("A tactical order already exists for this sector.");
                }
            }
        }

        // all ships sent must be valid
        foreach($data['pieces'] as $pieceId) {
            $piece = $game->findPieceInTurn($turn, $pieceId);
            if(!$piece) {
                throw new \Exception("A piece does not exist.");
            }
            if(!$this->validatePiece($piece, $tile, $player, $game)) {
                throw new \Exception("A piece ({$piece->id}) was sent that is not valid for this move ({$this->reason})");
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

    public function createOrder(Player $player, Game $game, $data)
    {
        $this->validateOrderAllowed($player, $game, $data);
        $order = new GivenOrder();
        $order->ownerId = $player->id;
        $order->orderType = self::TAG;
        $order->data = $data;

        return $order;
    }

    public function resolveOrder(Player $player, Game $game, GivenOrder $order)
    {
		$log = new TacticalOrderLog($order);
		$log->setTile($game->findTile($order->data['tile']));
		$log->addPlayer($player);

		foreach($order->data['pieces'] as $pieceId) {
			$piece = $this->pieceRepo->findByIdentifier($pieceId);
			// log the move
			$log->addPieceMoved($piece);
		}

		// handle construction
        foreach($order->data['newPieces'] as $pieceTypeId) {
		    $pieceType = new PieceType();
		    $pieceType->id = $pieceTypeId;
		    $log->addPieceBuilt($pieceType);
        }

		return $log;
    }

    public function getTag()
    {
        return self::TAG;
    }

    public function getPotentialPiecesForOrder(Tile $tile, Player $player, Game $game) {
        $pieces = $game->findCurrentPiecesForPlayer($player);
        $potentials = [];
        foreach($pieces as $piece) {
            if($this->validatePiece($piece, $tile, $player, $game)) {
                $potentials[] = $piece;
            }
        }
        return $potentials;
    }

    private function validatePiece(Piece $piece, Tile $tile, Player $player, Game $game) {
        // belong to the player
        if($player->id != $piece->ownerId) {
            $this->reason = 'wrong owner';
            return false;
        }

        // spaceborne
        if(!$this->pieceService->hasTrait($piece, Spaceborne::TAG)) {
            $this->reason = 'not spaceborne';
            return false;
        }
        // mobile or is cargo
        if(!$this->pieceService->hasTrait($piece, Mobile::TAG) || $this->pieceService->hasTrait($piece, Cargo::TAG)) {
            $this->reason = 'not mobile or cargo';
            return false;
        }

        // has no orders
        if(!$this->validateNoOrdersSet($piece, $player, $game)) {
            $this->reason = 'has orders';
            return false;
        }

        // in range (if not carried)
        if(!$this->pathfindingService->getInReach($piece, $tile) && !$this->pieceService->hasTrait($piece, Cargo::TAG) ) {
            $this->reason = 'out of range';
            return false;
        }

        return true;
    }

    private function validateNoOrdersSet(Piece $piece, Player $player, Game $game) {

        $otherOrders = $game->currentOrdersForPlayer($player);
        // not have any other tactical orders set
        foreach($otherOrders as $order) {
            if($order->orderType == self::TAG) {
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

    public function updateGamestate(Game $game, LoggerInterface $log)
    {
        $tile = $game->findTile($log->getTile());
        $turn = $game->currentTurn();
        foreach( $log->getMovedPieces() as $moved) {
            $piece = $game->findPieceInTurn($turn, $moved);
            $oldTile = $game->findTile($piece->tileId);
            foreach($oldTile->pieces as $key => $findPiece) {
                if($piece->id == $findPiece->id) {
                    unset($oldTile->pieces[$key]);
                    break;
                }
            }
            $tile->pieces[] = $piece;
            $piece->tileId = $tile->id;
        }

        foreach( $log->getBuiltPieces() as $pieceType ) {
            $piece = new Piece();
            $piece->typeId = $pieceType;
            $piece->ownerId = $log->getPlayer();
            $piece->tileId = $tile->id;
            $tile->pieces[] = $piece;
        }
    }

}