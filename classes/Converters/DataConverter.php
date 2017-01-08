<?php

namespace Plu\Converters;

class DataConverter implements ConverterInterface
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
        return json_encode($data);
    }

    public function fromDB($obj, $data)
    {
        return json_decode($data, true);
    }

}