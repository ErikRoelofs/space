<?php

namespace Plu\Service;


use Plu\Entity\ActiveObjective;
use Plu\Entity\Game;
use Plu\Objective\HasResourceObjective;

class ObjectiveCreator
{

    public function newObjective(Game $game, $types) {
        $objective = new ActiveObjective();
        $objective->type = $types[0]->getType();
        $objective->gameId = $game->id;
        $objective->turnId = $game->currentTurn()->id;
        $objective->value = 1;
        $objective->params = ['resource' => 'industry', 'amount' => 3 ];

        return $objective;
    }
}
