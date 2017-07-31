<?php

namespace Plu\Repository;

use Plu\Entity\Game;

class ClaimedObjectiveRepository extends BaseRepository
{
    public function __construct($db, $converter)
    {
        return parent::__construct($db, $converter, 'claimedObjective');
    }

    public function findByGame(Game $game) {
        $sql = "SELECT * FROM $this->tableName t INNER JOIN player ON player.id = t.playerId WHERE gameId = ?";
        $rows = $this->db->fetchAll($sql, array((int)$game->id));
        return $this->converter->batchFromDB($this->tableName, $rows);
    }

}
