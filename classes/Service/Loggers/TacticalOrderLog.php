<?php

namespace Plu\Service\Loggers;

class TacticalOrderLog implements LoggerInterface {

	private $data = [
		'player' => null,
		'moved' => [],
		'built' => []
	];

	private $givenOrder;

    /**
     * TacticalOrderLog constructor.
     * @param $givenOrder
     */
    public function __construct($givenOrder)
    {
        $this->givenOrder = $givenOrder;
    }

    public function addPlayer(Player $player) {
		$this->data['player'] = $player;
	}

	public function addPieceMoved($from, $piece) {
		$this->data['moved'] = [$from, $piece];
	}

	public function addPieceBuilt($piece) {
		$this->data['built'] = [$piece];
	}

	public function compileLog() {
		return $this->data;
	}

    public function getService()
    {
        return 'tactical-order-service';
    }

    public function getOrigin()
    {
        return self::ORIGIN_ORDER;
    }

    public function getOriginId()
    {
        return $this->givenOrder->id;
    }


}