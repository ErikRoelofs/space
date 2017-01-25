<?php

namespace Plu\PieceTrait\BuildRequirements;

use Plu\PieceTrait\TraitInterface;

class CostsResources implements TraitInterface
{
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
        return 'build.requirement.resources';
    }

    public function getTraitContent()
    {
        return $this->amount;
    }


}