<?php

namespace Plu\Repository;

class OpenGameRepository extends BaseRepository
{
    public function __construct($db, $converter)
    {
        return parent::__construct($db, $converter, 'openGame');
    }

}
