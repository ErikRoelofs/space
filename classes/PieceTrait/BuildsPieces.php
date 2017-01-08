<?php

namespace Plu\PieceTrait;

class BuildsPieces implements TraitInterface
{
    const TAG = 'builds';

    public function getTraitName()
    {
        return self::TAG;
    }

    public function getTraitContent()
    {
        return true;
    }

}