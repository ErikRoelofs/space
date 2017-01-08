<?php

namespace Plu\PieceTrait;

class Spaceborne implements TraitInterface
{

    const TAG = 'location.spaceborne';

    public function getTraitName()
    {
        return self::TAG;
    }

    public function getTraitContent()
    {
        return true;
    }

}