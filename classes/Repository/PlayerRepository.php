<?php

namespace Plu\Repository;


use Plu\Entity\Game;

class PlayerRepository extends BaseRepository
{
    public function __construct($db, $converter)
    {
        return parent::__construct($db, $converter, 'player');
    }

    public function findByGame(Game $game) {
        $sql = "SELECT * FROM $this->tableName WHERE gameId = ?";
        $rows = $this->db->fetchAll($sql, array((int) $game->id));
        return $this->converter->batchFromDB($this->tableName, $rows);
    }
}