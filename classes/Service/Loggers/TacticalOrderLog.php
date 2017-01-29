<?php

namespace Plu\Service\Loggers;

use Plu\Entity\Player;
use Plu\Entity\Tile;

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

    public function setTile(Tile $tile) {
        $this->data['tile'] = $tile;
    }

    public function addPlayer(Player $player) {
		$this->data['player'] = $player->id;
	}

	public function addPieceMoved($piece) {
		$this->data['moved'][] = $piece->id;
	}

	public function addPieceBuilt($pieceType) {
		$this->data['built'][] = $pieceType->id;
	}

	public function storeLog() {
		return $this->data;
	}

	public function getTile() {
        return $this->data['tile'];
    }

    public function getMovedPieces() {
        return $this->data['moved'];
    }

    public function getBuiltPieces() {
        return $this->data['built'];
    }

    public function getPlayer() {
        return $this->data['player'];
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