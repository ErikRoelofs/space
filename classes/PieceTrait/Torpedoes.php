<?php

namespace Plu\PieceTrait;

class Torpedoes implements TraitInterface
{

    const TAG = 'combat.weapon.torpedoes';

    private $shots;
    private $firepower;

    public function __construct($shots, $firepower) {
        $this->shots = $shots;
        $this->firepower = $firepower;
    }

    public function getTraitName()
    {
        return false;
    }

    public function getTraitContent()
    {
        return [
            'shots' => $this->shots,
            'firepower' => $this->firepower
        ];
    }


}