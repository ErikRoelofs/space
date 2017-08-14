<?php

namespace Plu\Service;


use Plu\Entity\User;

class PlayerService
{

    /**
     * @var User
     */
    protected $user;

    /**
     * PlayerService constructor.
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function canControlPlayer(Player $player) {
        return $this->user->id == $player->userId;
    }

}
