<?php

namespace Plu\Converters;

use Plu\Converters\Exception\RestrictedException;

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
            try {
                $json[$prop] = $rule->toJSON($data->{$prop});
            }
            catch(RestrictedException $e) {
                // do not put this property in $json
            }
        }
        return $json;
    }

    public function fromJSON($obj, $data)
    {

        foreach($this->rules as $prop => $rule) {
            try {
                $obj->{$prop} = $rule->fromJSON($obj, isset($data[$prop]) ? $data[$prop] : null);
            }
            catch(RestrictedException $e) {
                // do not put this property in $obj
            }
        }
        return $obj;
    }

    public function toDB($data)
    {
        $db = [];
        foreach($this->rules as $prop => $rule) {
            try {
                $json[$prop] = $rule->toDB($data->{$prop});
            }
            catch(RestrictedException $e) {
                // do not put this property in $json
            }
        }
        return $db;
    }

    public function fromDB($obj, $data)
    {
        foreach($this->rules as $prop => $rule) {
            try {
                $obj->{$prop} = $rule->fromDB($obj, isset($data[$prop]) ? $data[$prop] : null);
            }
            catch(RestrictedException $e) {
                // do not put this property in $obj
            }
        }
        return $obj;
    }

}
