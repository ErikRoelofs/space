<?php

namespace Plu\Service\Loggers;

use Plu\Service\SpaceBattleService;

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

    public function getClass()
    {
        return SpaceBattleService::class;
    }


}