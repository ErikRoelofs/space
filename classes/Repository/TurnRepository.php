<?php

namespace Plu\Repository;

class TurnRepository extends BaseRepository
{
    public function __construct($db, $converter)
    {
        return parent::__construct($db, $converter, 'turn');
    }

}