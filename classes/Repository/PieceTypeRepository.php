<?php

namespace Plu\Repository;

use Symfony\Component\Routing\Exception\ResourceNotFoundException;

class PieceTypeRepository extends BaseRepository
{
    public function __construct($db, $converter)
    {
        return parent::__construct($db, $converter, 'pieceType');
    }

    public function findByName($name) {
        $sql = "SELECT * FROM $this->tableName WHERE name = ?";
        $row = $this->db->fetchAssoc($sql, array($name));
        if(!$row) {
            throw new ResourceNotFoundException("Could not find $name in $this->tableName");
        }
        return $this->converter->fromDB($this->tableName, $row);
    }

}