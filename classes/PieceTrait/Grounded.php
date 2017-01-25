<?php

namespace Plu\PieceTrait;

class Grounded implements TraitInterface {

	const TAG = 'grounded';

	public function getTraitName() {
		return self::TAG;
	}

	public function getTraitContent() {
		return true;
	}

}