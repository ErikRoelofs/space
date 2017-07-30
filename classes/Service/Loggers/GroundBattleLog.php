<?php

namespace Plu\Service\Loggers;

use Plu\Service\GroundBattleService;

class GroundBattleLog extends AbstractBattleLog {

	public function compileLog() {
        return [
            'tile' => $this->tile,
            'hits' => $this->hits,
            'captures' => $this->captures
        ];
	}

    public function getClass()
    {
        return GroundBattleService::class;
    }

	public function getService() {
        return 'invasion-battle-service';
	}

	public function storeLog() {
		return $this->compileLog();
	}

    public function getHits() {
        return $this->hits;
    }

    public function getCaptures() {
        return $this->captures;
    }

    public function getLostCargo() {
	    return [];
    }
}
