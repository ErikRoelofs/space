<?php

namespace Plu\Repository;

use Plu\Entity\Game;

class LogRepository extends BaseRepository
{
    public function __construct($db, $converter)
    {
        return parent::__construct($db, $converter, 'log');
    }

}