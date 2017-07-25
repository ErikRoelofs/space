<?php

namespace Plu\Service\LogExpanders;


use Plu\Entity\Log;
use Plu\Repository\PieceRepository;
use Plu\Repository\PieceTypeRepository;
use Plu\Repository\TurnRepository;
use Plu\Service\ConverterService;
use Plu\Service\GameService;
use Plu\Service\PieceService;

class TacticalOrderLogExpander implements LogExpanderInterface
{

    /**
     * @var PieceRepository
     */
    private $pieceRepo;

    /**
     * TacticalOrderLogExpander constructor.
     * @param PieceRepository $pieceRepo
     */
    public function __construct(PieceRepository $pieceRepo)
    {
        $this->pieceRepo = $pieceRepo;
    }

    public function expand(Log $log)
    {
        $moved = [];
        foreach($log->results['moved'] as $id) {
            $moved[] = $this->pieceRepo->findByIdentifier($id);
        }

        return json_encode ([
            'player' => $log->results['player'],
            'tile' => $log->results['tile'],
            'moved' => $moved,
            'built' => $log->results['built']
        ]);
    }


}
