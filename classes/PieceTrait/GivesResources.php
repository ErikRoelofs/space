<?php

namespace Plu\PieceTrait;

class GivesResources implements TraitInterface {

	const TAG = 'resources';

	protected $industry;
	protected $social;

	/**
	 * GivesResources constructor.
	 *
	 * @param $industry
	 * @param $social
	 */
	public function __construct($industry, $social) {
		$this->industry = $industry;
		$this->social = $social;
	}

	public function getTraitName() {
		return self::TAG;
	}

	public function getTraitContent() {
		return [
			'industry' => $this->industry,
			'social' => $this->social
		];
	}

}