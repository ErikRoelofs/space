<?php

namespace Plu\Service\Loggers;

class GroundBattleLog extends AbstractBattleLog {

    protected $planetCap;

	public function logPlanetCaptured($planet, $newOwner) {
		$this->planetCap = [ $planet, $newOwner ];
	}

	public function compileLog() {
        return [
            'tile' => $this->tile,
            'hits' => $this->hits,
            'planet' => $this->planetCap
        ];
	}

}