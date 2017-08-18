<?php

namespace Plu\PieceTrait;

class UnitDescription implements TraitInterface
{
    const TAG = 'description';

    protected $name;
    protected $description;
    protected $image;

    /**
     * UnitDescription constructor.
     * @param $name
     * @param $description
     * @param $image
     */
    public function __construct($name, $description, $image)
    {
        $this->name = $name;
        $this->description = $description;
        $this->image = $image;
    }

    public function getTraitName() {
        return self::TAG;
    }

    public function getTraitContent() {
        return [
            'name' => $this->name,
            'description' => $this->description,
            'image' => $this->image
        ];
    }

}
