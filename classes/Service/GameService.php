<?php

namespace Plu\Service;

use Plu\Entity\Game;
use Plu\Entity\Player;
use Plu\Repository\BoardRepository;
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
     * @var BoardRepository
     */
    private $boardRepo;

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
     * @param BoardRepository $boardRepo
     * @param TileRepository $tileRepo
     * @param PieceRepository $pieceRepo
     * @param PlayerRepository $playerRepo
     * @param PieceTypeRepository $pieceTypeRepo
     * @param OrderRepository $orderRepository
     * @param TurnRepository $turnRepository
     */
    public function __construct(GameRepository $gameRepo, BoardRepository $boardRepo, TileRepository $tileRepo, PieceRepository $pieceRepo, PlayerRepository $playerRepo, PieceTypeRepository $pieceTypeRepo, OrderRepository $orderRepository, TurnRepository $turnRepository)
    {
        $this->gameRepo = $gameRepo;
        $this->boardRepo = $boardRepo;
        $this->tileRepo = $tileRepo;
        $this->pieceRepo = $pieceRepo;
        $this->playerRepo = $playerRepo;
        $this->pieceTypeRepo = $pieceTypeRepo;
        $this->orderRepository = $orderRepository;
        $this->turnRepository = $turnRepository;
    }

    public function buildGame($gameId) {
        $game = $this->gameRepo->findByIdentifier($gameId);
        $game->board = $this->boardRepo->findByGame($game);
        $game->players = $this->playerRepo->findByGame($game);
        $game->turns = $this->turnRepository->findCompletedByGame($game);
        $game->currentTurn = $this->turnRepository->getCurrentForGame($game);
        $game->pieceTypes = $this->pieceTypeRepo->findAll();

        $game->board->tiles = $this->tileRepo->findByBoard($game->board);
        foreach($game->board->tiles as $tile) {
            $tile->pieces = $this->pieceRepo->findByTile($tile);
            foreach($tile->pieces as $piece) {
                $piece->type = $this->pieceTypeRepo->findByIdentifier($piece->typeId);
            }
        }

        return $game;
    }

    public function buildGameFromPlayer(Player $player) {
        $id = $player->gameId;
        return $this->buildGame($id);
    }


}