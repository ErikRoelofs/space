<?php

namespace Plu\Repository;


class PlanetRepository extends BaseRepository
{
    public function __construct($db, $converter)
    {
        return parent::__construct($db, $converter, 'planet');
    }

}