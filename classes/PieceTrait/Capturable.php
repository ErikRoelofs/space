<?php

namespace Plu\PieceTrait;

class Capturable implements TraitInterface {

	const TAG = 'capturable';

	public function getTraitName() {
		return self::TAG;
	}

	public function getTraitContent() {
		return true;
	}

}