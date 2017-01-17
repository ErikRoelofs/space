<?php

namespace Plu\Service\Loggers;

class GroundBattleLog implements LoggerInterface{

	private $hits = [];
	private $planetCap;

	public function logHit($phase, $round, $by, $to) {
		$this->hits[] = [ $phase, $round, $by, $to ];
	}

	public function logPlanetCaptured($planet, $newOwner) {
		$this->planetCap = [ $planet, $newOwner ];
	}

	public function compileLog() {

	}

}