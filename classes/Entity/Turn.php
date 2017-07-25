<?php

namespace Plu\Entity;

class Turn
{
    public $id;
    public $number;
    public $gameId;
    public $orders = [];
    public $tiles = [];
    public $logs = [];

    public function getTileById($id) {
        foreach($this->tiles as $tile) {
            if($tile->id === $id) {
                return $tile;
            }
        }
    }
}
