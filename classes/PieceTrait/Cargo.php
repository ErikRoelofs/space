<?php

namespace Plu\PieceTrait;

class Cargo implements TraitInterface
{
    public function getTraitName()
    {
        return 'location.cargo';
    }

    public function getTraitContent()
    {
        return true;
    }

}