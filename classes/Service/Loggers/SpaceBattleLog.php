<?php

namespace Plu\Service\Loggers;

use Plu\Entity\Tile;

class SpaceBattleLog implements LoggerInterface{

    private $tile;
	private $hits = [];

    public function __construct(Tile $tile) {
        $this->tile = $tile;
    }

    public function logHit($phase, $round, $by, $to) {
		$this->hits[] = [ 'phase' => $phase, 'round' => $round, 'scoredBy' => $by, 'target' => $to ];
	}

	public function compileLog() {
		return [
		    'tile' => $this->tile,
            'hits' => $this->hits
        ];
	}

}