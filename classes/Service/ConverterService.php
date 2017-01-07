<?php

namespace Plu\Service;

class ConverterService
{

    private $converters;

    public function addConverter($converter){
        $this->converters[] = $converter;
    }

    public function toDB($object) {
        $array = [];
        foreach($object as $prop => $val) {
            $array[$prop] = $val;
        }
        return $array;
    }

    public function toJSON($object) {
        return json_encode($object);
    }

    public function fromDB($entityName, $data) {
        $classname = 'Plu\Entity\\' . $entityName;
        $obj = new $classname;
        foreach($data as $prop => $val) {
            $obj->$prop = $val;
        }
        return $obj;
    }

    public function fromJSON($entityName, $data) {
        var_dump($data);
        var_dump(json_decode($data, true));
        $classname = 'Plu\Entity\\' . $entityName;
        $class = new $classname;
        $data = json_decode($data, true);
        foreach($data as $item => $value) {
            $class->$item = $value;
        }
        return $class;
    }

    public function fromVoid($entityName) {
        $classname = 'Plu\Entity\\' . $entityName;
        $class = new $classname;
        return $class;
    }

}