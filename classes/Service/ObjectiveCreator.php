<?php

namespace Plu\Service;


use Plu\Entity\ActiveObjective;
use Plu\Entity\Game;
use Plu\Objective\ControlsCenterObjective;
use Plu\Objective\HasPiecesObjective;
use Plu\Objective\HasResourceObjective;

// @todo: should not use hardcoded IDs here, need to update HasPiecesObjective!
class ObjectiveCreator
{

    private $objectives = [
        [ 'type' => ControlsCenterObjective::TYPE, 'params' => [], 'value' => 1, 'turn' => 1 ],
        [ 'type' => HasResourceObjective::TYPE, 'params' => [ 'resource' => 'industry', 'amount' => 5 ], 'value' => 1, 'turn' => 1 ],
        [ 'type' => HasResourceObjective::TYPE, 'params' => [ 'resource' => 'social', 'amount' => 8 ], 'value' => 1, 'turn' => 1 ],
        // has planets
        [ 'type' => HasPiecesObjective::TYPE, 'params' => [ 'type' => 1, 'amount' => 3 ], 'value' => 1, 'turn' => 1 ],
        // has docks
        [ 'type' => HasPiecesObjective::TYPE, 'params' => [ 'type' => 5, 'amount' => 2 ], 'value' => 1, 'turn' => 1 ],

        [ 'type' => HasResourceObjective::TYPE, 'params' => [ 'resource' => 'industry', 'amount' => 10 ], 'value' => 2, 'turn' => 5 ],
        [ 'type' => HasResourceObjective::TYPE, 'params' => [ 'resource' => 'social', 'amount' => 15 ], 'value' => 2, 'turn' => 5 ],
        // has planets
        [ 'type' => HasPiecesObjective::TYPE, 'params' => [ 'type' => 1, 'amount' => 7 ], 'value' => 2, 'turn' => 5 ],
        // has docks
        [ 'type' => HasPiecesObjective::TYPE, 'params' => [ 'type' => 5, 'amount' => 3 ], 'value' => 2, 'turn' => 5 ],

        [ 'type' => HasResourceObjective::TYPE, 'params' => [ 'resource' => 'industry', 'amount' => 15 ], 'value' => 3, 'turn' => 9 ],
        [ 'type' => HasResourceObjective::TYPE, 'params' => [ 'resource' => 'social', 'amount' => 24 ], 'value' => 3, 'turn' => 9 ],
        // has planets
        [ 'type' => HasPiecesObjective::TYPE, 'params' => [ 'type' => 1, 'amount' => 12 ], 'value' => 3, 'turn' => 9 ],
        // has docks
        [ 'type' => HasPiecesObjective::TYPE, 'params' => [ 'type' => 5, 'amount' => 5 ], 'value' => 3, 'turn' => 9 ],
    ];

    public function newObjective(Game $game) {

        $potentials = [];
        foreach($this->objectives as $objective) {
            if($objective['turn'] <= $game->currentTurn()->number) {
                $potentials[] = $objective;
            }
        }
        return $this->makeObjectiveFromRow($game, $potentials[array_rand($potentials)]);
    }

    private function makeObjectiveFromRow($game, $row) {
        $objective = new ActiveObjective();
        $objective->type = $row['type'];
        $objective->gameId = $game->id;
        $objective->turnId = $game->currentTurn()->id;
        $objective->value = $row['value'];
        $objective->params = $row['params'];

        return $objective;
    }
}
