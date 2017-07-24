<?php

namespace Plu\Repository;

use Plu\Entity\Turn;

class LogRepository extends BaseRepository
{
    public function __construct($db, $converter)
    {
        return parent::__construct($db, $converter, 'log');
    }

    public function findByTurn(Turn $turn) {
        $sql = "SELECT * FROM $this->tableName WHERE turnId = ?";
        $rows = $this->db->fetchAll($sql, array((int) $turn->id));
        return $this->converter->batchFromDB($this->tableName, $rows);
    }

}
