<?php
namespace Plu\Repository;

class TempRepository extends BaseRepository
{

    public function __construct($db, $converter)
    {
        return parent::__construct($db, $converter, 'temp');
    }
}