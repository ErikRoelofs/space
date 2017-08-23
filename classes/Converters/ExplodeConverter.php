<?php

namespace Plu\Converters;


class ExplodeConverter implements ConverterInterface
{

    protected $delimiter;

    /**
     * ExplodeConverter constructor.
     * @param $delimiter
     */
    public function __construct($delimiter = ',')
    {
        $this->delimiter = $delimiter;
    }


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
        return implode($this->delimiter, $data);
    }

    public function fromDB($obj, $data)
    {
        return explode($this->delimiter, $data);
    }

}
