<?php

namespace Plu\Repository;

use Plu\Entity\Channel;

class ChannelUserRepository extends BaseRepository
{
    public function __construct($db, $converter)
    {
        return parent::__construct($db, $converter, 'channelUser');
    }

    public function findByChannel(Channel $channel) {
        $sql = "SELECT * FROM $this->tableName WHERE channelId = ?";
        $rows = $this->db->fetchAll($sql, array($channel->id));
        return $this->converter->batchFromDB($this->tableName, $rows);
    }
}
