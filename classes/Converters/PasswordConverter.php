<?php

namespace Plu\Converters;


class PasswordConverter implements ConverterInterface
{
    public function toJSON($data)
    {
        return null;
    }

    public function fromJSON($obj, $data)
    {
        return null;
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
