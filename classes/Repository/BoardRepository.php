<?php
namespace Plu\Repository;

class BoardRepository extends BaseRepository
{
    public function __construct($db, $converter)
    {
        return parent::__construct($db, $converter, 'board');
    }

}