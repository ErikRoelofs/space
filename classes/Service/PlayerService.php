<?php

namespace Plu\Service;


use Plu\Entity\Player;
use Plu\Entity\User;
use Plu\Repository\GameRepository;
use Plu\Repository\PlayerRepository;

class PlayerService
{

    /**
     * @var User
     */
    protected $user;

    /**
     * @var PlayerRepository
     */
    protected $playerRepo;

    /**
     * @var EndOfTurnService
     */
    protected $endOfTurn;

    /**
     * @var GameService
     */
    protected $gameService;

    /**
     * PlayerService constructor.
     * @param User $user
     * @param PlayerRepository $playerRepo
     * @param EndOfTurnService $endOfTurn
     * @param GameService $gameService
     */
    public function __construct(User $user, PlayerRepository $playerRepo, EndOfTurnService $endOfTurn, GameService $gameService)
    {
        $this->user = $user;
        $this->playerRepo = $playerRepo;
        $this->endOfTurn = $endOfTurn;
        $this->gameService = $gameService;
    }


    public function canControlPlayer(Player $player) {
        return $this->user->id == $player->userId;
    }

    public function setPlayerReady(Player $player) {
        $this->validateCanReadyOrUnready($player);

        $player->ready = true;
        $this->playerRepo->update($player);

        $game = $this->gameService->buildGame($player->gameId);
        $allPlayers = $this->playerRepo->findByGame($game);

        $done = true;
        foreach($allPlayers as $player) {
            if(!$player->ready) {
                $done = false;
            }
        };

        if($done) {
            $this->endOfTurn->endRound($game);
        }
    }

    public function setPlayerNotReady(Player $player) {
        $this->validateCanReadyOrUnready($player);

        $player->ready = false;
        $this->playerRepo->update($player);
    }

    private function validateCanReadyOrUnready(Player $player) {
        $game = $this->gameService->buildGame($player->gameId);
        if(!$game->active) {
            throw new \Exception("Cannot add a new order; game is not active");
        }
        $turn = $game->currentTurn();
        if($turn->endTime && new \DateTime() > $turn->endTime) {
            throw new \Exception("This turn is no longer active; cannot ready/unready");
        }
    }
}
