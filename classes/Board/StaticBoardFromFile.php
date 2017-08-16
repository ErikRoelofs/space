<?php

namespace Plu\Board;

use Plu\Entity\Piece;
use Plu\Entity\Tile;
use Plu\PieceTrait\GivesResources;

/**
 * Loads a static board from a configuration file and uses it.
 * @package Plu\Board
 */
class StaticBoardFromFile
{

    private $contents = [];

    public function __construct($file) {
        $this->contents = $this->loadFile($file);

    }

    private function loadFile($file) {
        return [];
    }

    public function getPlanet(Tile $tile) {
        foreach($this->contents as $content) {
            if($content['coords'] == implode(',', $tile->coordinates)) {
                if($content['planet']) {
                    $values = explode(',', $content['planet']);

                    $planet = new Piece();
                    $planet->typeId = $this->getPieceType()->id;
                    $planet->traits[] = new GivesResources(mt_rand($values[0]),mt_rand($values[1]));
                    return $planet;
                }
                else {
                    return null;
                }
            }
        }
    }
}
