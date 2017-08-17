<?php

namespace Plu\Service;

use Plu\Board\BoardCreator;
use Plu\Entity\Game;
use Plu\Entity\Tile;
use Plu\Entity\Turn;

class NewBoardService
{

    /**
     * @var BoardCreator
     */
    protected $creator;

    private $remainingPlayers = [];

    private $tileCoords = [
        [0,0, 'home'], [0,1], [0,2], [0,3, 'home'],
        [1,0], [1,1], [1,2], [1,3], [1,4],
        [2,0], [2,1], [2,2], [2,3], [2,4], [2,5],
        [3,0, 'home'], [3,1], [3,2], [3,3, 'center'], [3,4], [3,5], [3,6, 'home'],
        [4,1], [4,2], [4,3], [4,4], [4,5], [4,6],
        [5,2], [5,3], [5,4], [5,5], [5,6],
        [6,3, 'home'], [6,4], [6,5], [6,6, 'home']
    ];

    public function __construct(BoardCreator $creator)
    {
        $this->creator = $creator;
    }

    public function newBoard(Game $game, Turn $turn, $players) {
        $this->remainingPlayers = $players;
        shuffle($this->remainingPlayers);
        $tiles = [];
        foreach($this->tileCoords as $data) {
            $tiles[] = $this->newTile($data[0], $data[1], isset($data[2]) ? $data[2] : null, $turn);
        }
        $turn->tiles = $tiles;
        return $tiles;
    }

    private function newTile($x, $y, $special, Turn $turn) {
        $tile = new Tile();
        $tile->coordinates = [$x,$y];
        $planet = $this->creator->getPlanet($tile);
        if($planet) {
            if($special == 'home' && count($this->remainingPlayers)) {
                $planet->ownerId = array_pop($this->remainingPlayers)->id;
            }
            $tile->pieces[$turn->number] = [$planet];
        }
        return $tile;
    }

}
