<?php

namespace Plu\Service;

use Plu\Entity\Game;
use Plu\Entity\Piece;
use Plu\Entity\Player;
use Plu\Entity\Turn;
use Plu\Repository\PieceTypeRepository;

class StartingUnitService
{

	private $turn;

    /**
     * @var PieceTypeRepository
     */
    private $pieceTypeRepo;

    public function __construct($pieceTypeRepo)
    {
        $this->pieceTypeRepo = $pieceTypeRepo;
    }


    public function createStartingUnitsForGame(Game $game, Turn $turn) {
		$this->turn = $turn;
        $allPieces = [];
        foreach($game->players as $player) {
            $allPieces = array_merge($allPieces, $this->createStartingUnitsForPlayer($player));
        }
        return $allPieces;
    }

    private function createStartingUnitsForPlayer(Player $player) {
        $homeTile = $this->getHomeTileForPlayer($player);
        $pieces = [];
        $pieces[] = $this->addPieceToSpace($player, 'Destroyer', $homeTile);
        $pieces[] = $this->addPieceToSpace($player, 'Destroyer', $homeTile);
        $carrier = $this->addPieceToSpace($player, 'Carrier', $homeTile);
        $pieces[] = $carrier;
        $pieces[] = $this->addPieceToSpace($player, 'Fighter', $homeTile);
        $pieces[] = $this->addPieceToSpace($player, 'Fighter', $homeTile);
        $pieces[] = $this->addPieceToSpace($player, 'Fighter', $homeTile);
        $pieces[] = $this->addPieceToSpace($player, 'Fighter', $homeTile);

        return $pieces;
    }

    private function addPieceToSpace($player, $pieceName, $tile) {
        $pieceType = $this->pieceTypeRepo->findByName($pieceName);
        $piece = new Piece();
        $piece->ownerId = $player->id;
        $piece->turnId = $this->turn->id;
        $piece->tileId = $tile->id;
        $piece->typeId = $pieceType->id;
        return $piece;
    }

    private function getHomeTileForPlayer(Player $player) {
        foreach($this->turn->tiles as $tile) {
            if(isset($tile->pieces[$this->turn->number]) && count($tile->pieces[$this->turn->number]) && $tile->pieces[$this->turn->number][0]->ownerId == $player->id) {
                return $tile;
            }
        }
        throw new \Exception("No home tile could be located for " . var_export( $player, true ));
    }

}