<?php

namespace Plu\PieceTrait;

class Cargo implements TraitInterface
{

	const TAG = 'location.cargo';

    public function getTraitName()
    {
        return self::TAG;
    }

    public function getTraitContent()
    {
        return true;
    }

}