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
        $sql = "SELECT * FROM $this->tableName g INNER JOIN player p ON p.gameId = g.id INNER JOIN user u ON u.id = p.userId WHERE u.username = ?";
        $rows = $this->db->fetchAll($sql, array($user->getUsername()));
        return $this->converter->batchFromDB($this->tableName, $rows);
    }


}
