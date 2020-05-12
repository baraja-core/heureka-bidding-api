<?php

declare(strict_types=1);

namespace Baraja\HeurekaBiddingApi\Response;


interface Response
{
	/**
	 * @return mixed[]
	 */
	public function getRawData(): array;

	/**
	 * @param mixed[] $rawData
	 */
	public function setRawData(array $rawData): void;
}