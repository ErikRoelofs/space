<?php

namespace Plu\Repository;


use Plu\Entity\GivenOrder;

class ResolutionRepository extends BaseRepository
{
    public function __construct($db, $converter)
    {
        return parent::__construct($db, $converter, 'resolution');
    }

    public function findByOrder(GivenOrder $order) {
        $sql = "SELECT * FROM $this->tableName WHERE givenOrderId = ?";
        $row = $this->db->fetchAssoc($sql, array((int) $order->id));
        if(!$row) {
            return null;
        }
        return $this->converter->fromDB($this->tableName, $row);
    }

}