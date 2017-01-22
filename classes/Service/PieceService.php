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
	 * @var \Plu\Repository\PlanetRepository
	 */
	protected $planetRepo;

    /**
     * @var BoardRepository
     */
	protected $boardRepository;

    /**
     * PieceService constructor.
     * @param $pieceTypeRepo
     */
    public function __construct(PieceTypeRepository $pieceTypeRepo, PieceRepository $pieceRepo, PlanetRepository $planetRepo, BoardRepository $boardRepository )
    {
        $this->pieceTypeRepo = $pieceTypeRepo;
		$this->planetRepo = $planetRepo;
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
		$all = $this->pieceRepo->findByBoard($this->boardRepository->findByIdentifier($tile->boardId));
		$good = [];
		foreach ($all as $piece) {
			if ($this->pieceSpaceborneOnTile($piece, $tile)) {
				$good[] = $piece;
			}
			if ($this->piecePlanetBoundOnTile($piece, $tile)) {
				$good[] = $piece;
			}
			if ($this->pieceCarriedOnTile($piece, $tile)) {
				$good[] = $piece;
			}
		}
		return $good;
	}

	private function pieceSpaceborneOnTile(Piece $piece, Tile $tile) {
		return $piece->location['type'] == 'space' && $piece->location['coordinates'][0] == $tile->coordinates[0] && $piece->location['coordinates'][1] == $tile->coordinates[1];
	}

	private function piecePlanetBoundOnTile(Piece $piece, Tile $tile) {
		if ($piece->location['type'] == 'planet') {
			$planet = $this->planetRepo->findByIdentifier($piece->location['id']);
			if($planet->tileId == $tile->id) {
				return true;
			}
		}
		return false;
	}

	private function pieceCarriedOnTile(Piece $piece, Tile $tile) {
		if($piece->location['type'] == 'piece') {
			$otherPiece = $this->pieceRepo->findByIdentifier($piece->location['id']);
			return $this->pieceSpaceborneOnTile($otherPiece, $tile);
		}
		return false;
	}

}