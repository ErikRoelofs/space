<?php

namespace Plu\Service;


use Plu\Entity\PieceType;
use Plu\PieceTrait\Artillery;
use Plu\PieceTrait\Buildable;
use Plu\PieceTrait\BuildRequirements\PieceWithTag;
use Plu\PieceTrait\BuildRequirements\Resources;
use Plu\PieceTrait\BuildsPieces;
use Plu\PieceTrait\Capturable;
use Plu\PieceTrait\Cargo;
use Plu\PieceTrait\FightsGroundBattles;
use Plu\PieceTrait\FightsSpaceBattles;
use Plu\PieceTrait\FlakCannons;
use Plu\PieceTrait\GroundCannon;
use Plu\PieceTrait\MainCannon;
use Plu\PieceTrait\Mobile;
use Plu\PieceTrait\Spaceborne;
use Plu\PieceTrait\TileLimit;
use Plu\PieceTrait\TileUnique;
use Plu\PieceTrait\Tiny;
use Plu\PieceTrait\Torpedoes;
use Plu\PieceTrait\Transports;

class PieceTypesService
{

    public function loadPieceTypes() {
        $types = [];
		$types[] = $this->makePlanet();
        $types[] = $this->makeDestroyer();
        $types[] = $this->makeCarrier();
        $types[] = $this->makeFighter();
        $types[] = $this->makeSpacedock();
        $types[] = $this->makeDreadnought();
        $types[] = $this->makeCruiser();
        $types[] = $this->makeGroundForce();
        $types[] = $this->makeDefenseSystem();


        return $types;
    }

	private function makePlanet() {
		$type = new PieceType();
		$type->name = 'Planet';
		$type->traits = [];

		$type->traits[] = new Grounded();
		$type->traits[] = new Transports(100);
		$type->traits[] = new Capturable();
		$type->traits[] = new Resources(2,2);
		$type->traits[] = new TileLimit(1);
		$type->traits[] = new BuildsPieces(['SpaceDock']);

		return $type;

	}

    private function makeDestroyer() {
        $type = new PieceType();
        $type->name = 'Destroyer';
        $type->traits = [];

        $type->traits[] = new Spaceborne();
        $type->traits[] = new Mobile(2);
        $type->traits[] = new Buildable([new PieceWithTag(BuildsPieces::TAG), new Resources(1)]);
        $type->traits[] = new FightsSpaceBattles(1,1);
        $type->traits[] = new FlakCannons(2,2);
        $type->traits[] = new MainCannon(1,2);
		$type->traits[] = new CostsResources(1);

        return $type;

    }

    private function makeCarrier() {
        $type = new PieceType();
        $type->name = 'Carrier';
        $type->traits = [];

        $type->traits[] = new Spaceborne();
        $type->traits[] = new Mobile(1);
        $type->traits[] = new Transports(6);
        $type->traits[] = new Buildable([new PieceWithTag(BuildsPieces::TAG), new Resources(3)]);
        $type->traits[] = new FightsSpaceBattles(3,1);
        $type->traits[] = new MainCannon(1,2);
		$type->traits[] = new CostsResources(3);

        return $type;

    }

    private function makeFighter() {
        $type = new PieceType();
        $type->name = 'Fighter';
        $type->traits = [];

        $type->traits[] = new Cargo();
        $type->traits[] = new Buildable([new PieceWithTag(BuildsPieces::TAG), new Resources(0.5)]);
        $type->traits[] = new FightsSpaceBattles(1,1);
        $type->traits[] = new MainCannon(1,2);
        $type->traits[] = new Tiny();
		$type->traits[] = new CostsResources(0.5);

        return $type;
    }

    private function makeSpacedock() {
        $type = new PieceType();
        $type->name = 'SpaceDock';
        $type->traits = [];

        $type->traits[] = new Spaceborne();
		$type->traits[] = new BuildsPieces(['Destroyer', 'Fighter', 'Carrier', 'Cruiser', 'Dreadnought', 'GroundForce', 'DefenseSystem']);
		$type->traits[] = new TileLimit(1);
		$type->traits[] = new CostsResources(3);
        return $type;
    }

    private function makeCruiser() {
        $type = new PieceType();
        $type->name = 'Cruiser';
        $type->traits = [];

        $type->traits[] = new Spaceborne();
        $type->traits[] = new Mobile(2);
        $type->traits[] = new Buildable([new PieceWithTag(BuildsPieces::TAG), new Resources(2)]);
        $type->traits[] = new FightsSpaceBattles(2,1);
        $type->traits[] = new MainCannon(1,4);
		$type->traits[] = new CostsResources(2);

        return $type;

    }

    private function makeDreadnought() {
        $type = new PieceType();
        $type->name = 'Dreadnought';
        $type->traits = [];

        $type->traits[] = new Spaceborne();
        $type->traits[] = new Mobile(1);
        $type->traits[] = new Buildable([new PieceWithTag(BuildsPieces::TAG), new Resources(5)]);
        $type->traits[] = new FightsSpaceBattles(2,2);
        $type->traits[] = new MainCannon(1,6);
		$type->traits[] = new CostsResources(5);

        return $type;
    }

    private function makeGroundForce() {
        $type = new PieceType();
        $type->name = 'GroundForce';
        $type->traits = [];

        $type->traits[] = new Cargo();
        $type->traits[] = new Buildable([new PieceWithTag(BuildsPieces::TAG), new Resources(0.5)]);
        $type->traits[] = new FightsGroundBattles(1,1);
        $type->traits[] = new GroundCannon(1,2);
		$type->traits[] = new CostsResources(0.5);

        return $type;
    }

    private function makeDefenseSystem() {
        $type = new PieceType();
        $type->name = 'DefenseSystem';
        $type->traits = [];

        $type->traits[] = new Cargo();
        $type->traits[] = new Buildable([new PieceWithTag(BuildsPieces::TAG), new Resources(2)]);
        $type->traits[] = new Torpedoes(1,5);
        $type->traits[] = new Artillery(1,5);
		$type->traits[] = new TileLimit(3);
		$type->traits[] = new CostsResources(2);

        return $type;
    }

}