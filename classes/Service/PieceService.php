<?php

namespace Plu\Service;

use Plu\Entity\Board;
use Plu\Entity\Piece;
use Plu\Entity\Tile;
use Plu\Repository\BoardRepository;
use Plu\Repository\PieceRepository;
use Plu\Repository\PieceTypeRepository;
use Plu\Repository\PlanetRepository;

class PieceService
{

    /**
     * @var PieceTypeRepository
     */
    protected $pieceTypeRepo;

	/**
	 * @var \Plu\Repository\PieceRepository
	 */
	protected $pieceRepo;

    /**
     * @var BoardRepository
     */
	protected $boardRepository;

    /**
     * PieceService constructor.
     * @param $pieceTypeRepo
     */
    public function __construct(PieceTypeRepository $pieceTypeRepo, PieceRepository $pieceRepo, BoardRepository $boardRepository )
    {
        $this->pieceTypeRepo = $pieceTypeRepo;
		$this->pieceRepo = $pieceRepo;
		$this->boardRepository = $boardRepository;
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

	public function findByTile(Tile $tile) {
		return $this->pieceRepo->findByTile($tile);
	}

}