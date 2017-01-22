<?php
namespace Plu\Entity;

class Log
{
    public $id;
    public $turnId;
    public $service;
    public $origin; // "order" or "system"
    public $originId; // only set for orders
    public $results;
}