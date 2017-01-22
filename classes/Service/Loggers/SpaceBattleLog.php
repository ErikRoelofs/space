<?php

namespace Plu\Service\Loggers;

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
        ];
	}

}