<?php

namespace Plu\Entity;

class ActiveObjective
{

    public $id;
    public $gameId;
    public $turnId;
    public $value;
    public $params;
    public $type;

    public function getParam($name) {
        if(!isset($this->params[$name])) {
            throw new \Exception("Param $name does not exist on ActiveObjective.");
        }
        return $this->params[$name];
    }

}
