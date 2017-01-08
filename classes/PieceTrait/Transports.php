<?php

namespace Plu\PieceTrait;

class Transports implements TraitInterface
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
        return 'transports';
    }

    public function getTraitContent()
    {
        return $this->value;
    }

}