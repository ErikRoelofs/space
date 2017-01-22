<?php

namespace Plu\Service;

use Plu\Entity\Game;
use Plu\Entity\Log;

interface GamestateUpdate
{

    public function updateGamestate(Game $game, Log $log);

}