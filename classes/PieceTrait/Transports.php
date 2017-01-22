<?php

namespace Plu\PieceTrait;

class Transports implements TraitInterface
{
    private $value;

    const TAG = 'transports';


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
        return self::TAG;
    }

    public function getTraitContent()
    {
        return $this->value;
    }

}