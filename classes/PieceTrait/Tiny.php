<?php

namespace Plu\PieceTrait;

class Tiny implements TraitInterface {

	const TAG = 'tiny';

	public function getTraitName() {
		return self::TAG;
	}

	public function getTraitContent() {
		return true;
	}

}