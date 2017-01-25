<?php

namespace Plu\Repository;

use Plu\Entity\Board;
use Plu\Entity\Piece;
use Plu\Entity\Player;
use Plu\Entity\Tile;
use Plu\Entity\Turn;

class PieceRepository extends BaseRepository {
	public function __construct($db, $converter) {
		return parent::__construct($db, $converter, 'piece');
	}

	public function findByBoardAndTurn(Board $board, Turn $turn) {
		$sql = "SELECT * FROM $this->tableName WHERE boardId = ? and turnId = ?";
		$rows = $this->db->fetchAll($sql, array((int)$board->id,(int)$turn->id));
		return $this->converter->batchFromDB($this->tableName, $rows);
	}

	public function findByPlayerAndTurn(Player $player, Turn $turn) {
		$sql = "SELECT * FROM $this->tableName WHERE ownerId = ? and turnId = ?";
		$rows = $this->db->fetchAll($sql, array((int)$player->id), (int)$turn->id);
		return $this->converter->batchFromDB($this->tableName, $rows);
	}

	public function findByTileAndTurn(Tile $tile, Turn $turn) {
        $sql = "SELECT * FROM $this->tableName WHERE tileId = ? and turnId = ?";
        $rows = $this->db->fetchAll($sql, array((int)$tile->id, (int) $turn->id));
        return $this->converter->batchFromDB($this->tableName, $rows);
    }

}