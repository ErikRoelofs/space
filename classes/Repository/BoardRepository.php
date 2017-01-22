<?php
namespace Plu\Repository;

use Plu\Entity\Game;

class BoardRepository extends BaseRepository
{
    public function __construct($db, $converter)
    {
        return parent::__construct($db, $converter, 'board');
    }

	public function findByGame(Game $game) {
		$sql = "SELECT * FROM $this->tableName WHERE gameId = ?";
		$rows = $this->db->fetchAssoc($sql, array((int) $game->id));
		return $this->converter->fromDB($this->tableName, $rows);
	}

}