<?php

namespace Plu\Entity;


class Game
{
    public $id;
    public $turns;
    public $players;
    public $board;
    public $pieceTypes;
    public $orderTypes;

	public function currentTurn() {
		$highest = 0;
		$found = null;
		foreach($this->turns as $turn) {
			if($turn->number > $highest) {
				$highest = $turn->number;
				$found = $turn;
			}
		}
		return $found;
	}
}