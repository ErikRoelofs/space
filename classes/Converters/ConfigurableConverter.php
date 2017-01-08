<?php

namespace Plu\Converters;

class ConfigurableConverter implements ConverterInterface
{

    private $rules = [];

    public function __construct(array $rules) {
        $this->rules = $rules;
    }

    public function toJSON($data)
    {
        $json = [];
        foreach($this->rules as $prop => $rule) {
            $json[$prop] = $rule->toJSON($data->{$prop});
        }
        return $json;
    }

    public function fromJSON($obj, $data)
    {

        foreach($this->rules as $prop => $rule) {
            $obj->{$prop} = $rule->fromJSON($obj, $data[$prop]);
        }
        return $obj;
    }

    public function toDB($data)
    {
        $db = [];
        foreach($this->rules as $prop => $rule) {
            $db[$prop] = $rule->toDB($data->{$prop});
        }
        return $db;
    }

    public function fromDB($obj, $data)
    {
        foreach($this->rules as $prop => $rule) {
            $obj->{$prop} = $rule->fromDB($obj, $data[$prop]);
        }
        return $obj;
    }

}