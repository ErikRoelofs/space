<?php

namespace Plu\Service;

use Plu\Entity\OpenGame;
use Plu\Entity\SubscribedPlayer;
use Plu\Entity\User;
use Plu\Repository\OpenGameRepository;
use Plu\Repository\SubscribedPlayerRepository;

class LobbyService
{

    /**
     * @var User $user
     */
    protected $user;

    /**
     * @var SubscribedPlayerRepository
     */
    protected $subscibedRepo;

    /**
     * @var OpenGameRepository
     */
    protected $openGameRepo;

    /**
     * @var NewGameService
     */
    protected $newGameService;

    /**
     * LobbyService constructor.
     * @param User $user
     * @param SubscribedPlayerRepository $subscibedRepo
     * @param OpenGameRepository $openGameRepo
     * @param NewGameService $newGameService
     */
    public function __construct(User $user, SubscribedPlayerRepository $subscibedRepo, OpenGameRepository $openGameRepo, NewGameService $newGameService)
    {
        $this->user = $user;
        $this->subscibedRepo = $subscibedRepo;
        $this->openGameRepo = $openGameRepo;
        $this->newGameService = $newGameService;
    }

    public function joinGame(OpenGame $game, $password) {
        if(!$this->validatePassword($password, $game)) {
            throw new \Exception("Invalid password");
        }
        if(!$this->userCanJoin($game)) {
            throw new \Exception("Already joined");
        }

        $subscriber = new SubscribedPlayer();
        $subscriber->name = $this->user->name;
        $subscriber->openGameId = $game->id;
        $subscriber->userId = $this->user->id;

        $this->subscibedRepo->add($subscriber);

        return $subscriber;
    }

    public function validatePassword($password, OpenGame $game) {
        return !$game->password || $game->password == $password;
    }

    public function userCanJoin(OpenGame $game) {
        $subscribed = $this->subscibedRepo->findByOpenGame($game);
        foreach($subscribed as $subscriber) {
            if($subscriber->userId == $this->user->getId()) {
                return false;
            }
        }
        return true;
    }

    public function openGame($vpLimit, $password) {
        $game = new OpenGame();
        $game->password = $password;
        $game->vpLimit = $vpLimit;
        $game->userId = $this->user->id;

        $game = $this->openGameRepo->add($game);

        $subscriber = new SubscribedPlayer();
        $subscriber->userId = $this->user->id;
        $subscriber->name = $this->user->name;
        $subscriber->openGameId = $game->id;

        $subscriber = $this->subscibedRepo->add($subscriber);

        return $game;

    }

    public function launchGame(OpenGame $game) {
        if(!$this->user->id == $game->userId) {
            throw new \Exception("Only the host can start the game.");
        }

        $newGame = $this->newGameService->newGameFromOpenGame($game);

        foreach($this->subscibedRepo->findByOpenGame($game) as $subscriber) {
            $this->subscibedRepo->remove($subscriber);
        }
        $this->openGameRepo->remove($game);

        return $newGame;
    }
}
