<?php

namespace Plu\Service\Loggers;

use Plu\Entity\Tile;

abstract class AbstractBattleLog implements LoggerInterface
{
    protected $tile;
    protected $hits = [];

    public function __construct(Tile $tile) {
        $this->tile = $tile;
    }

    public function logHit($phase, $round, $by, $to) {
        $this->hits[] = [ 'phase' => $phase, 'round' => $round, 'scoredBy' => $by, 'target' => $to ];
    }

    abstract public function compileLog();

}