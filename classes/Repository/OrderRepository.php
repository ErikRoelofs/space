<?php

namespace Plu\Repository;


class OrderRepository extends BaseRepository
{
    public function __construct($db, $converter)
    {
        return parent::__construct($db, $converter, 'givenOrder');
    }

}