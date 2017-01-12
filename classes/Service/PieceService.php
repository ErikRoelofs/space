<?php

namespace Plu\Service;

use Plu\Entity\Piece;
use Plu\Repository\PieceTypeRepository;

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
        $pieceType = $this->pieceTypeRepo->findByIdentifier($piece->typeId);
        foreach($pieceType->traits as $trait) {
            if($trait->getTraitName() == $traitTag) {
                return $trait->getTraitContent();
            }
        }
        return null;
    }

}