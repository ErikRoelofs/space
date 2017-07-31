<?php

namespace Plu\Repository;

class ActiveObjectiveRepository extends BaseRepository
{
    public function __construct($db, $converter)
    {
        parent::__construct($db, $converter, 'activeObjective');
    }

}
