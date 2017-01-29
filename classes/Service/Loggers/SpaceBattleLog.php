<?php

namespace Plu\Service\Loggers;

use Plu\Service\SpaceBattleService;

class SpaceBattleLog extends AbstractBattleLog {

	private $lostCargo = [];

	public function logLostCargo($player, $piece) {
        $this->lostCargo[] = [ 'player' => $player->id, 'cargo' => $piece->id ];
    }

	public function compileLog() {
		return [
		    'tile' => $this->tile,
            'hits' => $this->hits,
            'lost-cargo' => $this->lostCargo,
			'captures' => $this->captures
        ];
	}

	public function getClass() {
		return SpaceBattleService::class;
	}

	public function getService() {
		return 'space-battle-service';
	}

	public function storeLog() {
		return $this->compileLog();
	}

	public function getTile() {
	    return $this->tile;
    }

	public function getHits() {
	    return $this->hits;
    }

    public function getLostCargo() {
	    return $this->lostCargo;
    }

    public function getCaptures() {
	    return $this->captures;
    }

}