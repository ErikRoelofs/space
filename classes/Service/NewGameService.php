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

        $players = $this->newPlayers($game, $numPlayers);
        foreach($players as $key => $player) {
            $this->app['player-repo']->add($player);
        }
        $game->players = $players;

        $board = $this->app['new-board-service']->newBoard($game, $players);
        $this->app['board-repo']->add($board);
        foreach($board->tiles as $tile) {
            $tile->boardId = $board->id;
            $this->app['tile-repo']->add($tile);
            if($tile->planet) {
                $tile->planet->tileId = $tile->id;
                $this->app['planet-repo']->add($tile->planet);
            }
        }
        $game->board = $board;

        $units = $this->app['starting-units-service']->createStartingUnitsForGame($game);
        foreach($units as $unit) {
            $this->app['piece-repo']->add($unit);
        }

        $turn = new Turn();
        $turn->gameId = $game->id;
        $turn->number = 1;
        $this->app['turn-repo']->add($turn);

    }

    private function newPlayers($game, $amount) {
        for($i = 0; $i < $amount ;$i++) {
            $player = new Player();
            $player->gameId = $game->id;
            $player->industry = 0;
            $player->social = 0;
            $players[] = $player;
        }
        return $players;
    }

}