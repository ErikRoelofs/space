<?php

namespace Plu\PieceTrait;

class Mobile implements TraitInterface
{

    const TAG = 'mobile';

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
        return self::TAG;
    }

    public function getTraitContent()
    {
        return $this->value;
    }


}