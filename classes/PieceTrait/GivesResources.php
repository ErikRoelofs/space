<?php

namespace Plu\PieceTrait;

use Plu\Service\ResourceService;

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
			ResourceService::INDUSTRY => $this->industry,
			ResourceService::SOCIAL => $this->social
		];
	}

}