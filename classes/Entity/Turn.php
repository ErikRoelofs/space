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
}
