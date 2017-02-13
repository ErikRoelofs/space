<?php

namespace Plu\Repository;

use Plu\Entity\Player;
use Plu\Entity\Turn;

class ResourceClaimRepository extends BaseRepository
{
    public function __construct($db, $converter)
    {
        return parent::__construct($db, $converter, 'resourceClaim');
    }

	public function findClaimsByPlayerTurnAndResource(Player $player, Turn $turn, $resource) {
		$sql = "SELECT * FROM $this->tableName WHERE ownerId = ? AND turnId = ? AND resource = ?";
		$rows = $this->db->fetchAll($sql, array((int) $player->id, (int) $turn->id, $resource));
		return $this->converter->batchFromDB($this->tableName, $rows);
	}

}