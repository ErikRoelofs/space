<?php

namespace Plu\PieceTrait;

class BuildsPieces implements TraitInterface
{
    const TAG = 'builds';

	protected $typeNames = [];

	/**
	 * BuildsPieces constructor.
	 *
	 * @param array $typeNames
	 */
	public function __construct(array $typeNames) {
		$this->typeNames = $typeNames;
	}

	public function getTraitName()
    {
        return self::TAG;
    }

    public function getTraitContent()
    {
        return $this->typeNames;
    }

}