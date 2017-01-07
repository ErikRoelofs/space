<?php

namespace Plu\Repository;


class PlayerRepository extends BaseRepository
{
    public function __construct($db, $converter)
    {
        return parent::__construct($db, $converter, 'player');
    }

}