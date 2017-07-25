<?php

namespace Plu\Service\LogExpanders;

use Plu\Entity\Log;

interface LogExpanderInterface
{
    public function expand(Log $log);
}
