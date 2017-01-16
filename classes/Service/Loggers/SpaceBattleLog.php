<?php

namespace Plu\Service\Loggers;

class SpaceBattleLog implements LoggerInterface{

	private $hits = [];

	public function logHit($phase, $round, $by, $to) {
		$this->hits[] = [ $phase, $round, $by, $to ];
	}

	public function compileLog() {
		return $this->hits;
	}

}