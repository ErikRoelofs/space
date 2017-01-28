<?php

namespace Plu\Service;

use Plu\Entity\Game;
use Plu\Entity\Player;
use Plu\Repository\GameRepository;
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
     * GameService constructor.
     * @param GameRepository $gameRepo
     * @param TileRepository $tileRepo
     * @param PieceRepository $pieceRepo
     * @param PlayerRepository $playerRepo
     * @param PieceTypeRepository $pieceTypeRepo
     * @param OrderRepository $orderRepository
     * @param TurnRepository $turnRepository
     */
    public function __construct(GameRepository $gameRepo, TileRepository $tileRepo, PieceRepository $pieceRepo, PlayerRepository $playerRepo, PieceTypeRepository $pieceTypeRepo, OrderRepository $orderRepository, TurnRepository $turnRepository)
    {
        $this->gameRepo = $gameRepo;
        $this->tileRepo = $tileRepo;
        $this->pieceRepo = $pieceRepo;
        $this->playerRepo = $playerRepo;
        $this->pieceTypeRepo = $pieceTypeRepo;
        $this->orderRepository = $orderRepository;
        $this->turnRepository = $turnRepository;
    }

    public function buildGame($gameId) {
        $game = $this->gameRepo->findByIdentifier($gameId);
        $game->players = $this->playerRepo->findByGame($game);
        $game->turns = $this->turnRepository->findByGame($game);
        $game->pieceTypes = $this->pieceTypeRepo->findAll();

        $tiles = $this->tileRepo->findByGame($game);
        foreach($game->turns as $turn) {
            foreach($tiles as $originalTile) {
                $tile = clone $originalTile;
                $tile->pieces = $this->pieceRepo->findByTileAndTurn($tile, $turn);
                foreach($tile->pieces as $piece) {
                    $piece->tile = $tile;
                }
                $turn->tiles[] = $tile;
            }
            $turn->orders = $this->orderRepository->findByTurn($turn);
        }

        return $game;
    }

    public function buildGameFromPlayer(Player $player) {
        $id = $player->gameId;
        return $this->buildGame($id);
    }


}