<?php

namespace Plu\Repository;


class ResolutionRepository extends BaseRepository
{
    public function __construct($db, $converter)
    {
        return parent::__construct($db, $converter, 'resolution');
    }

}