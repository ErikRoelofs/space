<?php

namespace Plu\Service;

use Plu\Entity\Game;
use Plu\Entity\Log;
use Plu\Service\Loggers\LoggerInterface;

interface GamestateUpdate
{

    public function updateGamestate(Game $game, LoggerInterface $log);

}