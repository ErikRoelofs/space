<?php

namespace Plu\Service\Loggers;

use Plu\Entity\Tile;

abstract class AbstractBattleLog implements LoggerInterface
{
    protected $tile;
    protected $hits = [];

	protected $captures = [];

    public function __construct(Tile $tile) {
        $this->tile = $tile->id;
    }

	public function logPieceCaptured($piece, $newOwner) {
		$this->captures[] = [ 'piece' => $piece->id, 'newOwner' => $newOwner];
	}

	public function logHit($phase, $round, $by, $to) {
        $this->hits[] = [ 'phase' => $phase, 'round' => $round, 'scoredBy' => $by, 'target' => $to->id ];
    }

    abstract public function compileLog();
    abstract public function getClass();

    public function getOrigin()
    {
        return self::ORIGIN_SYSTEM;
    }

    public function getOriginId()
    {
        return null;
    }

    public function getTile() {
        return $this->tile;
    }


}
