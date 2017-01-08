<?php

namespace Plu\Service;

use Plu\Entity\Planet;
use Plu\Entity\Player;

class NewPlanetService
{

    public function newHomePlanet(Player $player) {
        $planet = new Planet();
        $planet->industrial = 5;
        $planet->social = 5;
        $planet->ownerId = $player->id;
        return $planet;
    }

    public function newCenterPlanet() {
        $planet = new Planet();
        $planet->industrial = 0;
        $planet->social = 10;
        return $planet;
    }

    public function newRegularPlanet() {
        $planet = new Planet();
        $planet->industrial = mt_rand(0,4);
        $planet->social = mt_rand(0,4);
        return $planet;
    }

}