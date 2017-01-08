<?php

namespace Plu\PieceTrait;

class FightsSpaceBattles implements TraitInterface
{

    const TAG = 'combat.space';

    private $priority;
    private $defense;

    public function __construct($priority, $defense) {
        $this->priority = $priority;
        $this->defense = $defense;
    }

    public function getTraitName()
    {
        return false;
    }

    public function getTraitContent()
    {
        return [
            'priority' => $this->priority,
            'defense' => $this->defense
        ];
    }


}