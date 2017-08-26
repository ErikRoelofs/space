<?php

namespace Plu\Repository;

use Plu\Entity\Channel;

class ChannelMessageRepository extends BaseRepository
{
    public function __construct($db, $converter)
    {
        return parent::__construct($db, $converter, 'channelMessage');
    }

    public function findByChannel(Channel $channel) {
        $sql = "SELECT * FROM $this->tableName WHERE channelId = ? LIMIT 250";
        $rows = $this->db->fetchAll($sql, array($channel->id));
        return $this->converter->batchFromDB($this->tableName, $rows);
    }

}
