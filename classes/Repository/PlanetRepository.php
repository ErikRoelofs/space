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
        $row = $this->db->fetchAssoc($sql, array((int) $tile->id));
        if(!$row) {
            return null;
        }
        return $this->converter->fromDB($this->tableName, $row);
    }

}