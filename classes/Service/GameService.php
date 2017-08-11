<?php

namespace Plu\Service;

use Plu\Entity\Game;
use Plu\Entity\Player;
use Plu\Repository\ActiveObjectiveRepository;
use Plu\Repository\ClaimedObjectiveRepository;
use Plu\Repository\GameRepository;
use Plu\Repository\LogRepository;
use Plu\Repository\OrderRepository;
use Plu\Repository\PieceRepository;
use Plu\Repository\PieceTypeRepository;
use Plu\Repository\PlanetRepository;
use Plu\Repository\PlayerRepository;
use Plu\Repository\TileRepository;
use Plu\Repository\TurnRepository;

class GameService
{

    /**
     * @var GameRepository
     */
    private $gameRepo;

    /**
     * @var TileRepository
     */
    private $tileRepo;

    /**
     * @var PieceRepository
     */
    private $pieceRepo;

    /**
     * @var PlayerRepository
     */
    private $playerRepo;

    /**
     * @var PieceTypeRepository
     */
    private $pieceTypeRepo;

    /**
     * @var OrderRepository
     */
    private $orderRepository;

    /**
     * @var TurnRepository
     */
    private $turnRepository;

    /**
     * @var LogRepository
     */
    private $logRepository;

    /**
     * @var ActiveObjectiveRepository
     */
    private $activeObjectiveRepository;

    /**
     * @var ClaimedObjectiveRepository
     */
    private $claimedObjectiveRepository;

	private $builtGames = [];

    /**
     * GameService constructor.
     * @param GameRepository $gameRepo
     * @param TileRepository $tileRepo
     * @param PieceRepository $pieceRepo
     * @param PlayerRepository $playerRepo
     * @param PieceTypeRepository $pieceTypeRepo
     * @param OrderRepository $orderRepository
     * @param TurnRepository $turnRepository
     * @param LogRepository $logRepository
     * @param ActiveObjectiveRepository $activeObjectiveRepository
     * @param ClaimedObjectiveRepository $claimedObjectiveRepository
     */
    public function __construct(GameRepository $gameRepo, TileRepository $tileRepo, PieceRepository $pieceRepo, PlayerRepository $playerRepo, PieceTypeRepository $pieceTypeRepo, OrderRepository $orderRepository, TurnRepository $turnRepository, LogRepository $logRepository, ActiveObjectiveRepository $activeObjectiveRepository, ClaimedObjectiveRepository $claimedObjectiveRepository)
    {
        $this->gameRepo = $gameRepo;
        $this->tileRepo = $tileRepo;
        $this->pieceRepo = $pieceRepo;
        $this->playerRepo = $playerRepo;
        $this->pieceTypeRepo = $pieceTypeRepo;
        $this->orderRepository = $orderRepository;
        $this->turnRepository = $turnRepository;
        $this->logRepository = $logRepository;
        $this->activeObjectiveRepository = $activeObjectiveRepository;
        $this->claimedObjectiveRepository = $claimedObjectiveRepository;
    }

    public function buildGame($gameId) {
		if(!isset($this->builtGames[$gameId])) {
			$game = $this->gameRepo->findByIdentifier($gameId);
            $game->myPlayerId = null;
			$player = $this->playerRepo->findForCurrentUserByGame($game);
			if($player) {
			    $game->myPlayerId = $player->id;
            }

			$game->players = $this->playerRepo->findByGame($game);
			$game->turns = $this->turnRepository->findByGame($game);
			$game->pieceTypes = $this->pieceTypeRepo->findAll();
			$game->objectives = $this->activeObjectiveRepository->findByGame($game);
			$game->claimedObjectives = $this->claimedObjectiveRepository->findByGame($game);

			$tiles = $this->tileRepo->findByGame($game);
			foreach ($game->turns as $turn) {
				foreach ($tiles as $originalTile) {
					$tile = clone $originalTile;
					$tile->pieces = $this->pieceRepo->findByTileAndTurn($tile, $turn);
					foreach ($tile->pieces as $piece) {
						$piece->tile = $tile;
					}
					$turn->tiles[] = $tile;
				}
				$turn->orders = $this->orderRepository->findByTurn($turn);
				$turn->logs = $this->logRepository->findByTurn($turn);
			}
			$this->builtGames[$gameId] = $game;
		}
		return $this->builtGames[$gameId];
    }

    public function buildGameFromPlayer(Player $player) {
        $id = $player->gameId;
        return $this->buildGame($id);
    }

}
