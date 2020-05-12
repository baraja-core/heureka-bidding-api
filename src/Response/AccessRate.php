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


	/**
	 * @return int
	 */
	public function getId(): int
	{
		return $this->id;
	}


	/**
	 * @return string
	 */
	public function getSlot(): string
	{
		return $this->slot;
	}


	/**
	 * @return int
	 */
	public function getCount(): int
	{
		return $this->count;
	}
}