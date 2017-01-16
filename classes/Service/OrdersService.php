<?php

namespace Plu\Service;

use Plu\Entity\Player;
use Plu\Repository\GameRepository;
use Plu\Repository\OrderRepository;
use Plu\Repository\PlayerRepository;
use Plu\Repository\TurnRepository;

class OrdersService
{

	/**
	 * @var OrderRepository
	 */
    protected $orderRepo;

	/**
	 * @var TurnRepository
	 */
    protected $turnRepo;

	/**
	 * @var GameRepository
	 */
    protected $gameRepo;

	/**
	 * @var PlayerRepository
	 */
	protected $playerRepo;

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

	public function getActiveOrdersForGame(Game $game)
	{
		$players = $this->playerRepo->findByGame($game);
		$orders = [];
		foreach($players as $player) {
			$orders = array_merge($orders, $this->getActiveOrdersForPlayer($player));
		}
		return $orders;
	}


}