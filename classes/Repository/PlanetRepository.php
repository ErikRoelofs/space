<?php

namespace Plu\Repository;


use Plu\Entity\Tile;

class PlanetRepository extends BaseRepository
{
    public function __construct($db, $converter)
    {
        return parent::__construct($db, $converter, 'planet');
    }

    public function findByTile(Tile $tile) {
        $sql = "SELECT * FROM $this->tableName WHERE tileId = ?";
        $rows = $this->db->fetchAll($sql, array((int) $tile->id));
        return $this->converter->batchFromDB($this->tableName, $rows);
    }

}