<?php

namespace Plu\PieceTrait;

class GroundCannon implements TraitInterface
{

    const TAG = 'combat.weapon.ground';

    private $shots;
    private $firepower;

    public function __construct($shots, $firepower) {
        $this->shots = $shots;
        $this->firepower = $firepower;
    }

    public function getTraitName()
    {
        return self::TAG;
    }

    public function getTraitContent()
    {
        return [
            'shots' => $this->shots,
            'firepower' => $this->firepower
        ];
    }


}