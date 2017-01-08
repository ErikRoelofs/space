<?php

namespace Plu\OrderTypes;

use Plu\Entity\GivenOrder;
use Plu\Entity\Player;

interface OrderTypeInterface
{

    public function getTag();

    public function validateOrderAllowed(Player $player, $data);

    public function createOrder(Player $player, $data);

    public function resolveOrder(Player $player, GivenOrder $order);

}