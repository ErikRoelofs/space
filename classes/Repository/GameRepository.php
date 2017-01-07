<?php

namespace Plu\Repository;

class GameRepository extends BaseRepository
{
    public function __construct($db, $converter)
    {
        return parent::__construct($db, $converter, 'game');
    }

}