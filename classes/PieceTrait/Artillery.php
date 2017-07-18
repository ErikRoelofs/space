<?php

namespace Plu\PieceTrait;

class Artillery implements TraitInterface
{

    const TAG = 'combat.weapon.artillery';

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