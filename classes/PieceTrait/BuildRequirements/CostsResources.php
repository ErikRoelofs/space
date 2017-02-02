<?php

namespace Plu\PieceTrait\BuildRequirements;

use Plu\PieceTrait\TraitInterface;

class CostsResources implements TraitInterface
{

	const TAG = 'build.requirement.resources';
    private $amount;

    /**
     * PieceWithTag constructor.
     * @param $amount
     */
    public function __construct($amount)
    {
        $this->amount = $amount;
    }

    public function getTraitName()
    {
        return self::TAG;
    }

    public function getTraitContent()
    {
        return $this->amount;
    }


}