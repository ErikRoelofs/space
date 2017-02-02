<?php

namespace Plu\Service;

use Plu\Entity\Player;
use Plu\PieceTrait\GivesResources;

class ResourceService {

	const INDUSTRY = 'industry';
	const SOCIAL = 'social';

	/**
	 * @var PieceService
	 */
	protected $pieceService;

	/**
	 * @var GameService
	 */
	protected $gameService;

	/**
	 * ResourceService constructor.
	 * !OPERATES ON THE CURRENT TURN, ALWAYS!
	 *
	 * @param \Plu\Service\PieceService $pieceService
	 * @param \Plu\Service\GameService $gameService
	 */
	public function __construct(\Plu\Service\PieceService $pieceService, \Plu\Service\GameService $gameService) {
		$this->pieceService = $pieceService;
		$this->gameService = $gameService;
	}

	public function getInitialResources(Player $player) {
		return [
			self::INDUSTRY => $this->getInitialResource($player, self::INDUSTRY),
			self::SOCIAL => $this->getInitialResource($player, self::SOCIAL),
		];
	}

	public function getInitialResource(Player $player, $resource ) {
		// iterate all owned pieces; sum their resource production
		$game = $this->gameService->buildGame($player->gameId);
		$pieces = $game->findCurrentPiecesForPlayer($player);

		$amount = 0;

		foreach($pieces as $piece) {
			if($this->pieceService->hasTrait($piece, GivesResources::TAG )) {
				$amount += $this->pieceService->getTraitContents($piece, GivesResources::TAG)[$resource];
			}
		}
		return $amount;
	}

	public function getCurrentResource(Player $player, $resource) {
		$amount = $this->getInitialResource($player, $resource);
		// deduct all active claims
		foreach($this->getClaims($player, $resource) as $claim) {
			$amount -= $claim->getAmount();
		}
		return $amount;
	}

	public function getCurrentResources(Player $player) {
		return [
			self::INDUSTRY => $this->getCurrentResource($player, self::INDUSTRY),
			self::SOCIAL => $this->getCurrentResource($player, self::SOCIAL),
		];
	}

	public function hasResources(Player $player, $resource, $amount) {
		return $this->getCurrentResource($player, $resource) >= $amount;
	}

	public function claimResources(Player $player, $resource, $amount) {
		if(!$this->hasResources($player, $resource, $amount)) {
			throw new \Exception("Cannot claim resources; not available");
		}
		// do claim
	}

	public function cancelClaim($claim) {
		// remove claim
	}

	private function getClaims(Player $player, $resource) {
		// list claims
	}

}
