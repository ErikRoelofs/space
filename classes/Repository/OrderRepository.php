<?php

namespace Plu\Repository;


use Plu\Entity\Player;
use Plu\Entity\Turn;

class OrderRepository extends BaseRepository
{

    /**
     * @var ResourceClaimRepository
     */
    protected $claimRepository;

    public function __construct($db, $claimRepository, $converter)
    {
        $this->claimRepository = $claimRepository;
        return parent::__construct($db, $converter, 'givenOrder');
    }

    public function findByTurn(Turn $turn) {
        $sql = "SELECT * FROM $this->tableName WHERE turnId = ?";
        $rows = $this->db->fetchAll($sql, array((int) $turn->id));
        return $this->converter->batchFromDB($this->tableName, $rows);
    }

    public function findForPlayerAndTurn(Player $player, Turn $turn) {
        $sql = "SELECT * FROM $this->tableName WHERE ownerId = ? AND turnId = ?";
        $rows = $this->db->fetchAll($sql, array((int) $player->id, (int) $turn->id));
        return $this->converter->batchFromDB($this->tableName, $rows);
    }

    public function add($obj)
    {
        $obj = parent::add($obj);
        foreach($obj->claims as $claim) {
            $claim->orderId = $obj->id;
            $this->claimRepository->add($claim);
        }
        return $obj;
    }

    public function remove($obj)
    {
        foreach($obj->claims as $claim) {
            $this->claimRepository->remove($claim);
        }
        $obj = parent::remove($obj);
    }

    public function update($obj)
    {
        throw new \Exception("If you want to use this, implement updating the claims first!");
    }

    public function findByIdentifier($id)
    {
        $base = parent::findByIdentifier($id);
        $base->claims = $this->claimRepository->findClaimsByOrder($base);
        return $base;
    }


}
