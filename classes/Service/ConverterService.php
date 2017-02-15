<?php

namespace Plu\Service;

class ConverterService
{

    private $converters;

    public function addConverter($class, $converter){
        $this->converters[strtolower($class)] = $converter;
    }

    public function toDB($object) {
        return $this->getConverter(get_class($object))->toDB($object);
    }

    public function toJSON($object) {
        $obj = $this->getConverter(get_class($object))->toJSON($object);
        return json_encode($obj);
    }

    public function fromDB($entityName, $data) {
        $classname = '\Plu\Entity\\' . ucfirst($entityName);
        $obj = new $classname;
        return $this->getConverter($classname)->fromDB($obj, $data);
    }

    public function fromJSON($entityName, $data) {
        $classname = '\Plu\Entity\\' . $entityName;
        $obj = new $classname;
        $data = json_decode($data,true);
        return $this->getConverter($classname)->fromJSON($obj, $data);
    }

    public function batchFromDB($entity, $batch) {
        $objs = [];
        foreach($batch as $row) {
            $objs[] = $this->fromDB($entity, $row);
        }
        return $objs;
    }

    public function batchToJSON($objects) {
        $out = [];
        foreach($objects as $object) {
            $out[] = $this->getConverter(get_class($object))->toJSON($object);
        }
        return json_encode($out);
    }

    public function toJSONObject($object) {
        return $this->getConverter(get_class($object))->toJSON($object);
    }

    public function batchToJSONObject($objects) {
        $out = [];
        foreach($objects as $object) {
            $out[] = $this->getConverter(get_class($object))->toJSON($object);
        }
        return $out;
    }

    private function getConverter($classname) {
        $classname = $classname{0} == '\\' ? $classname : '\\' . $classname;
        if(!isset($this->converters[strtolower($classname)])) {
            throw new \Exception("No converter available for " . $classname);
        }
        return $this->converters[strtolower($classname)];
    }

}