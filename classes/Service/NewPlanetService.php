<?php

namespace Plu\Service;

use Plu\Entity\Piece;
use Plu\Entity\Planet;
use Plu\Entity\Player;
use Plu\PieceTrait\GivesResources;
use Plu\Repository\PieceTypeRepository;

class NewPlanetService
{

	/**
	 * @var PieceTypeRepository
	 */
	protected $pieceTypeRepo;

	/**
	 * NewPlanetService constructor.
	 *
	 * @param \Plu\Repository\PieceTypeRepository $pieceTypeRepo
	 */
	public function __construct(\Plu\Repository\PieceTypeRepository $pieceTypeRepo) {
		$this->pieceTypeRepo = $pieceTypeRepo;
	}

	public function newHomePlanet(Player $player) {
		$planet = new Piece();
		$planet->typeId = $this->getPieceType()->id;
		$planet->ownerId = $player->id;
		$planet->traits[] = new GivesResources(5,2);
		return $planet;
    }

    public function newCenterPlanet() {
		$planet = new Piece();
        $planet->typeId = $this->getPieceType()->id;
		$planet->traits[] = new GivesResources(0,8);
		return $planet;
    }

    public function newRegularPlanet() {
		$planet = new Piece();
        $planet->typeId = $this->getPieceType()->id;
		$planet->traits[] = new GivesResources(mt_rand(0,5),mt_rand(0,5));
		return $planet;
    }

	public function getPieceType() {
		return $this->pieceTypeRepo->findByName('Planet');
	}

}
