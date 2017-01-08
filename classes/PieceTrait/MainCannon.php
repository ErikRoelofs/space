<?php

namespace Plu\PieceTrait;

class MainCannon implements TraitInterface
{

    const TAG = 'combat.weapon.main';

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