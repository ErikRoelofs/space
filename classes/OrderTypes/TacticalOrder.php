<?php

namespace Plu\OrderTypes;

use Plu\Entity\Game;
use Plu\Entity\GivenOrder;
use Plu\Entity\Piece;
use Plu\Entity\PieceType;
use Plu\Entity\Player;
use Plu\Entity\ResourceClaim;
use Plu\Entity\Tile;
use Plu\PieceTrait\BuildRequirements\CostsResources;
use Plu\PieceTrait\BuildsPieces;
use Plu\PieceTrait\Cargo;
use Plu\PieceTrait\Mobile;
use Plu\PieceTrait\Spaceborne;
use Plu\PieceTrait\Transports;
use Plu\Repository\OrderRepository;
use Plu\Repository\PieceRepository;

use Plu\Repository\PieceTypeRepository;
use Plu\Repository\ResourceClaimRepository;
use Plu\Service\GamestateUpdate;
use Plu\Service\Loggers\LoggerInterface;
use Plu\Service\Loggers\TacticalOrderLog;
use Plu\Repository\TileRepository;
use Plu\Service\OrdersService;
use Plu\Service\PathfindingService;
use Plu\Service\PieceService;
use Plu\Service\ResourceService;

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
     * @var PieceTypeRepository
     */
    protected $pieceTypeRepo;

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
	 * @var ResourceService
	 */
	protected $resourceService;

    /**
     * TacticalOrder constructor.
     * @param $orderRepo
     */
    public function __construct(OrderRepository $orderRepo, OrdersService $ordersService, PieceRepository $pieceRepo, PieceTypeRepository $pieceTypeRepo, PieceService $pieceService, PathfindingService $pathfindingService, TileRepository $tileRepo, ResourceService $resourceService)
    {
        $this->orderRepo = $orderRepo;
        $this->ordersService = $ordersService;
        $this->pieceRepo = $pieceRepo;
        $this->pieceTypeRepo = $pieceTypeRepo;
        $this->pieceService = $pieceService;
        $this->pathfindingService = $pathfindingService;
        $this->tileRepo = $tileRepo;
		$this->resourceService = $resourceService;
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
		// collect piece objects
		$pieceObjects = [];
		foreach($data['pieces'] as $pieceId) {
			$pieceObjects[] = $game->findPieceInTurn($turn, $pieceId);
		}
		// all ships sent must be valid
        foreach($pieceObjects as $piece) {
            if(!$piece) {
                throw new \Exception("A piece does not exist.");
            }
            if(!$this->validatePiece($piece, $tile, $player, $game)) {
                throw new \Exception("A piece ({$piece->id}) was sent that is not valid for this move ({$this->reason})");
            }
        }

		// each moving fleet must have room for all its cargo
		$piecesByTile = [];
		foreach($pieceObjects as $piece) {
			// split out per tile of origin
			if(!isset($piecesByTile[$piece->tileId])) {
				$piecesByTile[$piece->tileId] = [];
			}
			$piecesByTile[$piece->tileId][] = $piece;
		}
		foreach($piecesByTile as $tileId => $pieces) {
			if(!$this->groupHasEnoughCargoSpace($pieces)) {
				throw new \Exception("Not enough cargo space for pieces from tile " . $pieces[0]->tileId);
			}
		}

		$pieceTypes = $this->getPieceTypesByIds($data['newPieces']);

        // all items queued for construction must be valid
        foreach($pieceTypes as $pieceType) {
            if(!$this->validateConstructionOrder($pieceType, $tile, $player )) {
                throw new \Exception("A construction order that was sent is not valid");
            }
        }

		// make sure there are enough resources
		if(!$this->validateEnoughResources($pieceTypes, $tile, $player)) {
			throw new \Exception("Not enough resources available.");
		}
    }

    private function getPieceTypesByIds($ids) {
        $pieceTypes = [];
        foreach($ids as $pieceTypeId) {
            $pieceType = $this->pieceTypeRepo->findByIdentifier($pieceTypeId);
            $pieceTypes[] = $pieceType;
        }
        return $pieceTypes;
    }

	private function groupHasEnoughCargoSpace($pieces) {
		$cargoAllowed = 0;
		$cargoUsed = 0;
		foreach($pieces as $piece) {
			if($this->pieceService->hasTrait($piece, Cargo::TAG)) {
				$cargoUsed++;
			}
			if($this->pieceService->hasTrait($piece, Transports::TAG)) {
				$cargoAllowed += $this->pieceService->getTraitContents($piece, Transports::TAG);
			}
		}
		return $cargoUsed <= $cargoAllowed;
	}

    public function createOrder(Player $player, Game $game, $data)
    {
        $this->validateOrderAllowed($player, $game, $data);
        $order = new GivenOrder();
        $order->ownerId = $player->id;
        $order->turnId = $game->currentTurn()->id;
        $order->orderType = self::TAG;
        $order->data = $data;


        $claim = new ResourceClaim();
        $claim->turnId = $game->currentTurn()->id;
        $claim->amount = $this->getTotalCost($this->getPieceTypesByIds($data['newPieces']), $game->findTile($data['tile']), $player);
        $claim->resource = 'industry';
        $claim->ownerId = $player->id;
        $order->claims[] = $claim;

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

    public function getBuildablePieceTypesForOrder(Tile $tile, Player $player, Game $game) {
        $buildable = [];
        foreach($game->pieceTypes as $type) {
            if($this->canBuildPieceType($tile, $player, $type)) {
                $buildable[] = $type;
            }
        }
        return $buildable;
    }

    private function canBuildPieceType(Tile $tile, Player $player, PieceType $type) {
        foreach($tile->pieces as $piece) {
            if($piece->ownerId === $player->id) {
                if ($this->pieceService->hasTrait($piece, BuildsPieces::TAG)) {
                    foreach ($this->pieceService->getTraitContents($piece, BuildsPieces::TAG) as $buildable) {
                        if ($buildable === $type->name) {
                            return true;
                        }
                    }
                }
            }
        }
        return false;
    }

    private function validatePiece(Piece $piece, Tile $tile, Player $player, Game $game) {
        // belong to the player
        if($player->id != $piece->ownerId) {
            $this->reason = 'wrong owner';
            return false;
        }

        // spaceborne (or cargo)
        if(!$this->pieceService->hasTrait($piece, Spaceborne::TAG || $this->pieceService->hasTrait($piece, Cargo::TAG))) {
            $this->reason = 'not spaceborne';
            return false;
        }
        // mobile or is cargo
        if(!($this->pieceService->hasTrait($piece, Mobile::TAG) || $this->pieceService->hasTrait($piece, Cargo::TAG))) {
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
		$buildable = [];
		$ok = false;
		foreach($tile->pieces as $piece) {
			if($this->pieceService->hasTrait($piece, BuildsPieces::TAG)) {
				$buildable = array_merge($buildable, $this->pieceService->getTraitContents($piece, BuildsPieces::TAG));
			}
		}
		foreach($buildable as $name) {
            if ($type->name == $name) {
                $ok = true;
            }
        }
		if(!$ok) {
			throw new \Exception("A new piece of type $type->name cannot be built here; not supported by tile.");
		}
		return true;
    }

	private function getTotalCost($pieceTypes, Tile $tile, Player $player) {
		$totalCost = 0;
		foreach($pieceTypes as $type) {
			// be affordable
			$piece = new Piece();
			$piece->typeId = $type->id;
			$piece->tileId = $tile->id;
			$piece->ownerId = $player->id;

			if (!$this->pieceService->hasTrait($piece, CostsResources::TAG)) {
				throw new \Exception("Trying to build a piece with no listed cost? This is probably a settings bug.");
			}
			$totalCost += $this->pieceService->getTraitContents($piece, CostsResources::TAG);
		}
		return $totalCost;
	}

	private function validateEnoughResources($pieceTypes, Tile $tile, Player $player) {
		return $this->resourceService->hasResources($player, ResourceService::INDUSTRY, $this->getTotalCost($pieceTypes, $tile, $player));
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
