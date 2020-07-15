<?php

declare(strict_types=1);

namespace Baraja\HeurekaBiddingApi\Response;


final class AccessRate
{

	/** @var int */
	private $id;

	/** @var string */
	private $slot;

	/** @var int */
	private $count;


	/**
	 * @param int $id
	 * @param string $slot
	 * @param int $count
	 */
	public function __construct(int $id, string $slot, int $count)
	{
		$this->id = $id;
		$this->slot = $slot;
		$this->count = $count;
	}


	public function getId(): int
	{
		return $this->id;
	}


	public function getSlot(): string
	{
		return $this->slot;
	}


	public function getCount(): int
	{
		return $this->count;
	}
}
