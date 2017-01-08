<?php

namespace Plu\Service;

use Plu\Entity\Tile;

class NewBoardService
{

    /**
     * @var NewPlanetService
     */
    private $planetService;

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

    public function __construct($planetService)
    {
        $this->planetService = $planetService;
    }

    public function newBoard($players) {
        $this->remainingPlayers = $players;
        shuffle($this->remainingPlayers);
        $tiles = [];
        foreach($this->tileCoords as $data) {
            $tiles[] = $this->newTile($data[0], $data[1], isset($data[2]) ? $data[2] : null);
        }
        return $tiles;
    }

    private function newTile($x, $y, $special) {
        $planets = [];
        if($special == 'home') {
            $planets[] = $this->planetService->newHomePlanet(array_pop($this->remainingPlayers));
        }
        if($special == 'center') {
            $planets[] = $this->planetService->newCenterPlanet();
        }
        if(!$special) {
            $c = mt_rand(0,2);
            for($i = 0; $i < $c; $i++) {
                $planets[] = $this->planetService->newRegularPlanet();
            }
        }
        $tile = new Tile();
        $tile->planets = $planets;
        $tile->coordinates = [$x,$y];
        return $tile;
    }

}