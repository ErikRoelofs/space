<?php

namespace Plu\Entity;


class Game
{
    public $id;
    public $turns;
    public $players;
    public $pieceTypes;
    public $orderTypes;
    public $objectives;
    public $claimedObjectives;

	public function currentTurn() {
		$highest = 0;
		$found = null;
		foreach($this->turns as $turn) {
			if($turn->number > $highest) {
				$highest = $turn->number;
				$found = $turn;
			}
		}
		return $found;
	}

	public function getTurn($turnId) {
	    foreach($this->turns as $turn) {
	        if($turn->id == $turnId) {
	            return $turn;
            }
        }
    }

	public function currentOrdersForPlayer(Player $player) {
	    $turn = $this->currentTurn();
	    $out = [];
	    foreach($turn->orders as $order) {
	        if($order->ownerId == $player->id) {
	            $out[] = $order;
            }
        }
        return $out;
    }

    public function findTile($id) {
        $turn = $this->currentTurn();
        foreach($turn->tiles as $tile) {
            if($tile->id == $id) {
                return $tile;
            }
        }
        return null;
    }

    public function findPieceInTurn(Turn $turn, $pieceId) {
        foreach($turn->tiles as $tile) {
            foreach ($tile->pieces as $piece) {
                if ($piece->id == $pieceId) {
                    return $piece;
                }
            }
        }
    }

    public function findCurrentPiecesForPlayer(Player $player) {
	    $turn = $this->currentTurn();
	    $out = [];
	    foreach($turn->tiles as $tile) {
            foreach ($tile->pieces as $piece) {
                if ($piece->ownerId == $player->id) {
                    $out[] = $piece;
                }
            }
        }
        return $out;
    }

    public function currentOrders() {
        return $this->currentTurn()->orders;
    }

    public function findPlayer($id) {
	    foreach($this->players as $player) {
	        if($player->id == $id) {
	            return $player;
            }
        }
    }

    public function findObjective($id) {
        foreach($this->objectives as $objective) {
            if($objective->id == $id) {
                return $objective;
            }
        }
    }

    public function findClaimsByPlayer(Player $player) {
        $out = [];
        foreach($this->claimedObjectives as $claimed) {
            if($claimed->playerId == $player->id) {
                $out[] = $claimed;
            }
        }
        return $out;
    }


}
