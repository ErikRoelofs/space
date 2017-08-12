<?php

namespace Plu\Repository;

use Plu\Entity\OpenGame;

class SubscribedPlayerRepository extends BaseRepository
{
    public function __construct($db, $converter)
    {
        return parent::__construct($db, $converter, 'subscribedPlayer');
    }

    public function findByOpenGame(OpenGame $game)
    {
        $sql = "SELECT * FROM $this->tableName WHERE openGameId = ?";
        $rows = $this->db->fetchAll($sql, array((int) $game->id));
        return $this->converter->batchFromDB($this->tableName, $rows);
    }

}
