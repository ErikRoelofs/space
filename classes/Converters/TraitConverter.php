<?php

namespace Plu\Converters;

class TraitConverter implements ConverterInterface
{
    public function toJSON($data)
    {
        $out = [];
        foreach($data as $trait) {
            $out[$trait->getTraitName()] = $trait->getTraitContent();
        }
        return $out;
    }

    public function fromJSON($obj, $data)
    {
        throw new \Exception("Traits are sent as information only and cannot be modified by the client.");
    }

    public function toDB($data)
    {
        return serialize($data);
    }

    public function fromDB($obj, $data)
    {
        return unserialize($data);
    }

}