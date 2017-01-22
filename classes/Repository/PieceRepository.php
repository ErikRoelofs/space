<?php

namespace Plu\Repository;

use Plu\Entity\Board;
use Plu\Entity\Piece;
use Plu\Entity\Player;
use Plu\Entity\Tile;

class PieceRepository extends BaseRepository {
	public function __construct($db, $converter) {
		return parent::__construct($db, $converter, 'piece');
	}

	public function findByBoard(Board $board) {
		$sql = "SELECT * FROM $this->tableName WHERE boardId = ?";
		$rows = $this->db->fetchAll($sql, array((int)$board->id));
		return $this->converter->batchFromDB($this->tableName, $rows);
	}

	public function findByPlayer(Player $player) {
		$sql = "SELECT * FROM $this->tableName WHERE ownerId = ?";
		$rows = $this->db->fetchAll($sql, array((int)$player->id));
		return $this->converter->batchFromDB($this->tableName, $rows);
	}

	public function findByTile(Tile $tile) {
        $sql = "SELECT * FROM $this->tableName WHERE tileId = ?";
        $rows = $this->db->fetchAll($sql, array((int)$tile->id));
        return $this->converter->batchFromDB($this->tableName, $rows);
    }

}