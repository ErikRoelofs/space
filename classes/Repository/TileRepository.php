<?php

namespace Plu\Repository;

class TileRepository extends BaseRepository
{
    public function __construct($db, $converter)
    {
        return parent::__construct($db, $converter, 'tile');
    }

}