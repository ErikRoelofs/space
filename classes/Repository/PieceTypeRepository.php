<?php

namespace Plu\Repository;

class PieceTypeRepository extends BaseRepository
{
    public function __construct($db, $converter)
    {
        return parent::__construct($db, $converter, 'pieceType');
    }

}