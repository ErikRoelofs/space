<?php

namespace Plu\Repository;

use Plu\Entity\Game;

class TurnRepository extends BaseRepository
{
    public function __construct($db, $converter)
    {
        return parent::__construct($db, $converter, 'turn');
    }

    public function findCompletedByGame(Game $game) {
        $sql = "SELECT * FROM $this->tableName WHERE gameId = ? ORDER BY number";
        $rows = $this->db->fetchAll($sql, array((int) $game->id));
        unset($rows[count($rows)-1]);
        return $this->converter->batchFromDB($this->tableName, $rows);
    }

    public function getCurrentForGame(Game $game) {
        $sql = "SELECT * FROM $this->tableName WHERE gameId = ? ORDER BY number DESC LIMIT 1";
        $row = $this->db->fetchAssoc($sql, array((int) $game->id));
        return $this->converter->fromDB($this->tableName, $row);
    }


}