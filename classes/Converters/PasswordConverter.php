<?php

namespace Plu\Converters;


use Plu\Converters\Exception\RestrictedException;

class PasswordConverter implements ConverterInterface
{
    public function toJSON($data)
    {
        throw new RestrictedException("Passwords are not exported");
    }

    public function fromJSON($obj, $data)
    {
        throw new RestrictedException("Passwords are not imported");
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
