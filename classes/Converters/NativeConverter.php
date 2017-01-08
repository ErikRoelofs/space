<?php

namespace Plu\Converters;


class NativeConverter implements ConverterInterface
{
    public function toJSON($data)
    {
        return $data;
    }

    public function fromJSON($obj, $data)
    {
        return $data;
    }

    public function toDB($data)
    {
        return $data;
    }

    public function fromDB($obj, $data)
    {
        return $data;
    }

}