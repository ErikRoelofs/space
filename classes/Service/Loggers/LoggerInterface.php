<?php

namespace Plu\Service\Loggers;

interface LoggerInterface {

    const ORIGIN_ORDER = 'order';
    const ORIGIN_SYSTEM = 'system';

    public function getService();

	public function compileLog();

	public function getOrigin();

	public function getOriginId();

}