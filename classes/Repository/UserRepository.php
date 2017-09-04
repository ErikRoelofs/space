<?php

namespace Plu\Repository;

use Symfony\Component\Routing\Exception\ResourceNotFoundException;

class UserRepository extends BaseRepository
{
    public function __construct($db, $converter)
    {
        return parent::__construct($db, $converter, 'user');
    }

    public function findByIdentifier($id)
    {
        $sql = "SELECT * FROM $this->tableName WHERE id = ?";
        $row = $this->db->fetchAssoc($sql, array((int) $id));
        if(!$row) {
            throw new ResourceNotFoundException("Could not find $id in $this->tableName");
        }
        return $this->converter->fromDB($this->tableName, $row);
    }

    public function findByUsername($username) {
        $sql = "SELECT * FROM $this->tableName WHERE username = ?";
        $row = $this->db->fetchAssoc($sql, array($username));
        if(!$row) {
            throw new ResourceNotFoundException("Could not find $username in $this->tableName");
        }
        return $this->converter->fromDB($this->tableName, $row);
    }

    public function findNumberOfUsers() {
        $sql = "SELECT COUNT(1) as count FROM $this->tableName";
        $row = $this->db->fetchAssoc($sql);
        return $row['count'];
    }

}
