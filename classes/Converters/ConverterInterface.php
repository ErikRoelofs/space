<?php

namespace Plu\Converters;

interface ConverterInterface
{
    public function toJSON($data);
    public function fromJSON($obj, $data);

    public function toDB($data);
    public function fromDB($obj, $data);
}