<?php

namespace Plu\PieceTrait;


class Buildable implements TraitInterface
{

    private $requirements;

    public function __construct($requirements) {
        $this->requirements = $requirements;
    }

    public function getTraitName()
    {
        return 'buildable';
    }

    public function getTraitContent()
    {
        return $this->requirements;
    }

}