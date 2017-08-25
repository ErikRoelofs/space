<?php

namespace Plu\Service;

use Plu\Entity\Piece;
use Plu\Repository\PieceTypeRepository;
use Plu\Repository\PlanetRepository;

class PieceService
{

    /**
     * @var PieceTypeRepository
     */
    protected $pieceTypeRepo;

    /**
     * PieceService constructor.
     * @param $pieceTypeRepo
     */
    public function __construct(PieceTypeRepository $pieceTypeRepo)
    {
        $this->pieceTypeRepo = $pieceTypeRepo;
    }


    public function hasTrait(Piece $piece, $traitTag)
    {
        return $this->getTraitContents($piece, $traitTag) !== null;
    }

    public function getTraitContents(Piece $piece, $traitTag)
    {
		$allTraits = $this->getAllTraits($piece);

        foreach($allTraits as $trait) {
            if($trait->getTraitName() == $traitTag) {
                return $trait->getTraitContent();
            }
        }
        return null;
    }

    public function getAllTraits(Piece $piece) {
        $allTraits = $piece->traits;
        $pieceType = $this->pieceTypeRepo->findByIdentifier($piece->typeId);
        $allTraits = array_merge($allTraits, $pieceType->traits);

        return $allTraits;
    }

}
