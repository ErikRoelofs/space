<?php

namespace Plu\Service;

use Plu\Entity\OpenGame;
use Plu\Repository\SubscribedPlayerRepository;
use Symfony\Component\Security\Core\User\User;

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
     * LobbyService constructor.
     * @param User $user
     * @param SubscribedPlayerRepository $subscibedRepo
     */
    public function __construct(User $user, SubscribedPlayerRepository $subscibedRepo)
    {
        $this->user = $user;
        $this->subscibedRepo = $subscibedRepo;
    }

    public function joinGame(OpenGame $game, $password) {
        if(!$this->validatePassword($password, $game)) {
            throw new \Exception("Invalid password");
        }
        if(!$this->userCanJoin($game)) {
            throw new \Exception("Already joined");
        }
    }

    public function validatePassword($password, OpenGame $game) {
        return !$game->password || $game->password == $password;
    }

    public function userCanJoin(OpenGame $game) {
        $subscribed = $this->subscibedRepo->findByOpenGame($game);
        foreach($subscribed as $subscriber) {
            if($subscriber->userId == $this->user->getUsername()) {
                return false;
            }
        }
        return true;
    }

}
