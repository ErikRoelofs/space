<?php

namespace Plu\Repository;

use Plu\Entity\User;

class GameRepository extends BaseRepository
{
    public function __construct($db, $converter)
    {
        return parent::__construct($db, $converter, 'game');
    }

    public function findForUser(User $user) {
        $sql = "SELECT g.* FROM $this->tableName g INNER JOIN player p ON p.gameId = g.id INNER JOIN user u ON u.id = p.userId WHERE u.username = ?";
        $rows = $this->db->fetchAll($sql, array($user->getUsername()));
        return $this->converter->batchFromDB($this->tableName, $rows);
    }

    public function findNumberOfActiveGames() {
        $sql = "SELECT COUNT(1) as count FROM $this->tableName g WHERE g.active = 1";
        $row = $this->db->fetchAssoc($sql);
        return $row['count'];
    }

    public function findNumberOfArchivedGames() {
        $sql = "SELECT COUNT(1) as count FROM $this->tableName g WHERE g.active = 0";
        $row = $this->db->fetchAssoc($sql);
        return $row['count'];
    }


}
