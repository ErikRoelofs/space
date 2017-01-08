<?php

namespace Plu\Repository;


use Plu\Entity\Player;
use Plu\Entity\Turn;

class OrderTypeRepository extends BaseRepository
{
    public function __construct($db, $converter)
    {
        return parent::__construct($db, $converter, 'orderType');
    }

}