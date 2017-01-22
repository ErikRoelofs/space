<?php

namespace Plu\PieceTrait;

class FightsSpaceBattles implements TraitInterface
{

    const TAG = 'combat.space';

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
        return self::TAG;
    }

    public function getTraitContent()
    {
        return [
            self::PRIORITY => $this->priority,
            self::DEFENSE => $this->defense
        ];
    }

}