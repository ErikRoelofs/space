<?php

namespace Plu\Service\Loggers;

use Plu\Service\SpaceBattleService;

class SpaceBattleLog extends AbstractBattleLog {

	private $lostCargo = [];

	public function logLostCargo($player, $piece) {
        $this->lostCargo[] = [ 'player' => $player, 'cargo' => $piece ];
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
		// TODO: Implement getService() method.
	}

	public function storeLog() {
		// TODO: Implement storeLog() method.
	}

}