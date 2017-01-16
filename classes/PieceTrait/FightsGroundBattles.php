<?php

namespace Plu\PieceTrait;

class FightsGroundBattles implements TraitInterface
{

    const TAG = 'combat.ground';

	const PRIORITY = 'priority';
	const DEFENSE = 'defense';

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
            self::PRIORITY => $this->priority,
            self::DEFENSE => $this->defense
        ];
    }

}