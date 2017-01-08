<?php

namespace Plu\PieceTrait;

class Mobile implements TraitInterface
{
    private $value;

    /**
     * Mobile constructor.
     * @param $value
     */
    public function __construct($value)
    {
        $this->value = $value;
    }

    public function getTraitName()
    {
        return 'mobile';
    }

    public function getTraitContent()
    {
        return $this->value;
    }


}