<?php

namespace Plu\Service;

use Plu\Entity\Game;
use Plu\Entity\OpenGame;
use Plu\Entity\Player;
use Plu\Entity\Turn;

class NewGameService
{

    private $app;

    public function __construct($app)
    {
        $this->app = $app;
    }

    public function newGameFromOpenGame(OpenGame $openGame) {
        $subscribers = $this->app['subscribed-player-repo']->findByOpenGame($openGame);
        return $this->newGame($subscribers, $openGame->vpLimit);
    }

    protected function newGame(array $subscribers, $vpLimit = 10) {
        $game = new Game();
        $game->vpLimit = $vpLimit;
        $game->active = 1;
        $this->app['game-repo']->add($game);

		$turn = new Turn();
		$turn->gameId = $game->id;
		$turn->number = 1;
		$this->app['turn-repo']->add($turn);

        $players = $this->makePlayersFromSubscribedPlayers($subscribers, $game);
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

        return $game;
    }

    private function makePlayersFromSubscribedPlayers(array $subscribers, Game $game) {
        foreach($subscribers as $i => $subscriber) {
            $player = new Player();
            $player->gameId = $game->id;
            $player->name = $subscriber->name;
            $player->color = $this->getColor($i);
            $player->userId = $subscriber->userId;
            $player->ready = 0;
            $players[] = $player;
        }
        return $players;
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
