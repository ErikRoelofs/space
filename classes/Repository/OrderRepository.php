<?php

namespace Plu\Repository;


use Plu\Entity\Player;
use Plu\Entity\Turn;

class OrderRepository extends BaseRepository
{
    public function __construct($db, $converter)
    {
        return parent::__construct($db, $converter, 'givenOrder');
    }

    public function findByTurn(Turn $turn) {
        $sql = "SELECT * FROM $this->tableName WHERE turnId = ?";
        $rows = $this->db->fetchAll($sql, array((int) $turn->id));
        return $this->converter->batchFromDB($this->tableName, $rows);
    }

    public function findForPlayerAndTurn(Player $player, Turn $turn) {
        $sql = "SELECT * FROM $this->tableName WHERE ownerId = ? AND turnId = ?";
        $rows = $this->db->fetchAll($sql, array((int) $player->id, (int) $turn->id));
        return $this->converter->batchFromDB($this->tableName, $rows);
    }

}