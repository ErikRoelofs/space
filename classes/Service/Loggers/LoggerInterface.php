<?php

namespace Plu\Service\Loggers;

interface LoggerInterface {

    const ORIGIN_ORDER = 'order';
    const ORIGIN_SYSTEM = 'system';

    public function getService();

	public function storeLog();

	public function getOrigin();

	public function getOriginId();

}