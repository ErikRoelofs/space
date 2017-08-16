<?php

namespace Plu\Board;


use Plu\Entity\Tile;

interface BoardCreator
{

    public function getPlanet(Tile $tile);

}
