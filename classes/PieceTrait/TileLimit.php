<?php

namespace Plu\PieceTrait;

class TileLimit implements TraitInterface {

	const TAG = 'tile.limit';

	protected $limit;

	/**
	 * TileLimit constructor.
	 *
	 * @param $limit
	 */
	public function __construct($limit) {
		$this->limit = $limit;
	}

	public function getTraitName() {
		return self::TAG;
	}

	public function getTraitContent() {
		return $this->limit;
	}

}