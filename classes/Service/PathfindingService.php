<?php

namespace Plu\Service;


use Plu\Entity\Piece;
use Plu\Entity\Tile;
use Plu\PieceTrait\Mobile;

class PathfindingService
{

    /**
     * @var PieceService
     */
    private $pieceService;

    /**
     * PathfindingService constructor.
     * @param PieceService $pieceService
     */
    public function __construct(PieceService $pieceService)
    {
        $this->pieceService = $pieceService;
    }


    public function getInReach(Piece $piece, Tile $tile) {

        $currentLocation = $piece->location['coordinates'];
        $targetLocation = $tile->coordinates;

        $distance1 = $currentLocation[0] - $targetLocation[0];
        $distance2 = $currentLocation[1] - $targetLocation[1];
        $distance3 = $distance1 - $distance2;

        $realDistance = max(abs($distance1), abs($distance2), abs($distance3));

        $speed = $this->pieceService->getTraitContents($piece, Mobile::TAG);

        return $speed >= $realDistance;
    }

}