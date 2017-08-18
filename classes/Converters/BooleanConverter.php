<?php

namespace Plu\Converters;


class BooleanConverter implements ConverterInterface
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
        return $data ? 1 : 0;
    }

    public function fromDB($obj, $data)
    {
        return (bool) $data;
    }

}
