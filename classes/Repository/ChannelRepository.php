<?php

namespace Plu\Repository;

use Plu\Entity\User;

class ChannelRepository extends BaseRepository
{
    public function __construct($db, $converter)
    {
        return parent::__construct($db, $converter, 'channel');
    }

    public function findForUser(User $user) {
        $sql = "SELECT c.* FROM $this->tableName c INNER JOIN channelUser cu ON c.id = cu.userId";
        $rows = $this->db->fetchAll($sql, array($user->id));
        return $this->converter->batchFromDB($this->tableName, $rows);
    }

}
