<?php

namespace Plu\Service\LogExpanders;


use Plu\Entity\Log;
use Plu\Repository\PieceRepository;
use Plu\Repository\TurnRepository;
use Plu\Service\ConverterService;
use Plu\Service\GameService;
use Plu\Service\PieceService;

class SpaceCombatLogExpander implements LogExpanderInterface
{

    /**
     * @var GameService
     */
    private $gameService;

    /**
     * @var ConverterService
     */
    private $converterService;

    /**
     * @var TurnRepository
     */
    private $turnRepository;

    /**
     * @var PieceRepository
     */
    private $pieceRepository;

    /**
     * SpaceCombatLogExpander constructor.
     * @param GameService $gameService
     * @param ConverterService $converterService
     * @param TurnRepository $turnRepository
     * @param PieceRepository $pieceRepository
     */
    public function __construct(GameService $gameService, ConverterService $converterService, TurnRepository $turnRepository, PieceRepository $pieceRepository)
    {
        $this->gameService = $gameService;
        $this->converterService = $converterService;
        $this->turnRepository = $turnRepository;
        $this->pieceRepository = $pieceRepository;
    }


    public function expand(Log $log)
    {
        $turn = $this->turnRepository->findByIdentifier($log->turnId);
        $game = $this->gameService->buildGame($turn->gameId);

        $turn = $game->getTurn($log->turnId);
        $tile = $turn->getTileById($log->results['tile']);
        $remainingPieces = $this->converterService->batchToJSONObject($tile->pieces);
        $initialPieces = $this->collectInitialPieces($tile, $log);

        foreach($log->results['hits'] as $key => $hit) {
            $log->results['hits'][$key]['target'] = $this->converterService->toJSONObject($this->pieceRepository->findByIdentifier($hit['target']));
        }

        $expanded = [
            'hits' => $log->results['hits'],
            'captures' => $log->results['captures'],
            'lost-cargo' => $log->results['lost-cargo'],
            'initialPieces' => $this->converterService->batchToJSONObject($initialPieces),
            'remainingPieces' => $remainingPieces,
        ];

        return json_encode($expanded);
    }

    private function collectInitialPieces($tile, $log) {
        $initialPieces = $tile->pieces;
        foreach($log->results['hits'] as $hit) {
            if(!$this->pieceIdIn($initialPieces, $hit['target'])) {
                $initialPieces[] = $this->pieceRepository->findByIdentifier($hit['target']);
            }
        }
        return $initialPieces;
    }

    private function pieceIdIn($pieces, $id) {
        foreach($pieces as $piece) {
            if($piece->id === $id) {
                return true;
            }
        }
        return false;
    }

}
