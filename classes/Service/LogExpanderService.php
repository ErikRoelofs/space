<?php

namespace Plu\Service;


use Plu\Entity\Log;
use Plu\Service\LogExpanders\LogExpanderInterface;

class LogExpanderService
{

    private $expanders = [];

    public function addExpander($type, LogExpanderInterface $expander) {
        $this->expanders[$type] = $expander;
    }

    public function expand(Log $log) {
        if(!isset($this->expanders[$log->service])) {
            throw new \Exception("Could not expand log with service: " . $log->service . ", no expander defined.");
        }
        return $this->expanders[$log->service]->expand($log);
    }
}
