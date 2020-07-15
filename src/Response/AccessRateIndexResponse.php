<?php

declare(strict_types=1);

namespace Baraja\HeurekaBiddingApi\Response;


/**
 * Statistic information about calling API endpoint.
 */
final class AccessRateIndexResponse extends BaseResponse
{

	/** @var int */
	private $count;

	/** @var AccessRate[] */
	private $accessRates = [];


	/**
	 * @param int $count
	 * @param mixed[] $accessRates
	 */
	public function __construct(int $count, array $accessRates)
	{
		$this->count = $count;

		foreach ($accessRates as $accessRate) {
			$this->accessRates[] = new AccessRate($accessRate['id'], $accessRate['slot'], $accessRate['count']);
		}
	}


	public function getCount(): int
	{
		return $this->count;
	}


	/**
	 * @return AccessRate[]
	 */
	public function getAccessRates(): array
	{
		return $this->accessRates;
	}
}
