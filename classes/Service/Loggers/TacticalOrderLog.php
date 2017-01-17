<?php

namespace Plu\Service\Loggers;

class TacticalOrderLog implements LoggerInterface {

	private $data = [
		'player' => null,
		'moved' => [],
		'built' => []
	];

	public function addPlayer(Player $player) {
		$this->data['player'] = $player;
	}

	public function addPieceMoved($from, $piece) {
		$this->data['moved'] = [$from, $piece];
	}

	public function addPieceBuilt($piece) {
		$this->data['built'] = [$piece];
	}


	public function compileLog() {
		return $this->data;
	}

}