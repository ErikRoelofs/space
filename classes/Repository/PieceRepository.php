<?php

namespace Plu\Repository;

class PieceRepository extends BaseRepository
{
    public function __construct($db, $converter)
    {
        return parent::__construct($db, $converter, 'piece');
    }

}