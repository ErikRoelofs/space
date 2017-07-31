<?php
namespace Plu\Entity;

class Log
{
    public $id;
    public $turnId;
    public $service;
    public $origin; // references the origin type (such as an entity, order, or just "system")
    public $originId; // an id belonging to the origin type
    public $results;
}
