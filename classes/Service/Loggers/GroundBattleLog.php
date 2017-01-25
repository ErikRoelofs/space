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
		// TODO: Implement getService() method.
	}

	public function storeLog() {
		// TODO: Implement storeLog() method.
	}

}