<?php

namespace Plu\Board;

use Plu\Entity\Piece;
use Plu\Entity\Tile;
use Plu\PieceTrait\GivesResources;
use Symfony\Component\Yaml\Yaml;

/**
 * Loads a static board from a configuration file and uses it.
 * @package Plu\Board
 */
class StaticBoardFromFile implements BoardCreator
{

    private $contents = [];

    public function __construct($file) {
        $this->contents = $this->loadFile($file)['board'];
    }

    private function loadFile($file) {
        return Yaml::parse(file_get_contents($file));
    }

    public function getPlanet(Tile $tile) {

        foreach($this->contents as $content) {
            if($content['coords'] == implode(',', $tile->coordinates)) {
                if($content['planet']) {
                    $values = explode(',', $content['planet']);

                    $planet = new Piece();
                    $planet->typeId = 1; // @todo: get from repo
                    $planet->traits[] = new GivesResources($values[0],$values[1]);
                    return $planet;
                }
                else {
                    return null;
                }
            }
        }
    }

}
