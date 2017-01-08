<?php

namespace Plu\Service;


use Plu\Entity\PieceType;
use Plu\PieceTrait\Cargo;
use Plu\PieceTrait\Mobile;
use Plu\PieceTrait\Spaceborne;
use Plu\PieceTrait\Transports;

class PieceTypesService
{

    public function loadPieceTypes() {
        $types = [];
        $types[] = $this->makeDestroyer();
        $types[] = $this->makeCarrier();
        $types[] = $this->makeFighter();

        return $types;
    }

    private function makeDestroyer() {
        $type = new PieceType();
        $type->name = 'Destroyer';
        $type->traits = [];

        $type->traits[] = new Spaceborne();
        $type->traits[] = new Mobile(2);

        return $type;

    }

    private function makeCarrier() {
        $type = new PieceType();
        $type->name = 'Carrier';
        $type->traits = [];

        $type->traits[] = new Spaceborne();
        $type->traits[] = new Mobile(1);
        $type->traits[] = new Transports(6);

        return $type;

    }

    private function makeFighter() {
        $type = new PieceType();
        $type->name = 'Fighter';
        $type->traits = [];

        $type->traits[] = new Cargo();

        return $type;
    }

}