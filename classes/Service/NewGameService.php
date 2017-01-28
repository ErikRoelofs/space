<?php

namespace Plu\Service;

use Plu\Entity\Game;
use Plu\Entity\Player;
use Plu\Entity\Turn;

class NewGameService
{

    private $app;

    public function __construct($app)
    {
        $this->app = $app;
    }

    public function newGame($numPlayers) {
        $game = new Game();
        $this->app['game-repo']->add($game);

		$turn = new Turn();
		$turn->gameId = $game->id;
		$turn->number = 1;
		$this->app['turn-repo']->add($turn);

		$players = $this->newPlayers($game, $numPlayers);
        foreach($players as $key => $player) {
            $this->app['player-repo']->add($player);
        }
        $game->players = $players;

        $tiles = $this->app['new-board-service']->newBoard($game, $turn, $players);
        foreach($tiles as $tile) {
            $tile->gameId = $game->id;
            $this->app['tile-repo']->add($tile);
            if(count($tile->pieces)) {
				// there is only 1, which is the planet
				$planet = $tile->pieces[$turn->number][0];
                $planet->tileId = $tile->id;
				$planet->turnId = $turn->id;
                $this->app['piece-repo']->add($planet);
            }
        }

        $units = $this->app['starting-units-service']->createStartingUnitsForGame($game, $turn);
        foreach($units as $unit) {
			$unit->turnId = $turn->id;
            $this->app['piece-repo']->add($unit);
        }


    }

    private function newPlayers($game, $amount) {
        for($i = 0; $i < $amount ;$i++) {
            $player = new Player();
            $player->gameId = $game->id;
            $player->name = $this->makeName($i);
            $player->color = $this->getColor($i);
            $players[] = $player;
        }
        return $players;
    }

    private function makeName($i) {
        $colors = [
            'John',
            'Paul',
            'Anna',
            'Sarah',
            'Mike',
            'Amber',
        ];
        return $colors[$i];
    }

    private function getColor($i) {
        $colors = [
            '#ff0000',
            '#ffff00',
            '#ff00ff',
            '#00ff00',
            '#00ffff',
            '#0000ff',
        ];
        return $colors[$i];
    }

}