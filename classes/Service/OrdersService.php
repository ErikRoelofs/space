<?php

namespace Plu\Service;

use Plu\Entity\Player;

class OrdersService
{

    protected $orderRepo;
    protected $turnRepo;
    protected $gameRepo;

    /**
     * OrdersService constructor.
     * @param $orderRepo
     * @param $turnRepo
     * @param $gameRepo
     */
    public function __construct($orderRepo, $turnRepo, $gameRepo)
    {
        $this->orderRepo = $orderRepo;
        $this->turnRepo = $turnRepo;
        $this->gameRepo = $gameRepo;
    }


    public function getActiveOrdersForPlayer(Player $player)
    {
        $game = $this->gameRepo->findByIdentifier($player->gameId);
        return $this->orderRepo->findForPlayerAndTurn($player, $this->turnRepo->getCurrentForGame($game));
    }


}