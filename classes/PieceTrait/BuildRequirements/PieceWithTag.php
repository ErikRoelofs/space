<?php

namespace Plu\PieceTrait\BuildRequirements;

use Plu\PieceTrait\TraitInterface;

class PieceWithTag implements TraitInterface
{
    private $content;

    /**
     * PieceWithTag constructor.
     * @param $content
     */
    public function __construct($content)
    {
        $this->content = $content;
    }

    public function getTraitName()
    {
        return 'build.requirement.piecewithtag';
    }

    public function getTraitContent()
    {
        return $this->content;
    }


}