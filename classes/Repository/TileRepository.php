<?php

namespace Plu\Repository;

use Plu\Entity\Board;

class TileRepository extends BaseRepository
{
    public function __construct($db, $converter)
    {
        return parent::__construct($db, $converter, 'tile');
    }

    public function findByBoard(Board $board) {
        $sql = "SELECT * FROM $this->tableName WHERE boardId = ?";
        $rows = $this->db->fetchAll($sql, array((int) $board->id));
        return $this->converter->batchFromDB($this->tableName, $rows);
    }

}