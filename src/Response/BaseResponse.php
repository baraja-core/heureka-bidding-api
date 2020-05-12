<?php

declare(strict_types=1);


namespace Baraja\HeurekaBiddingApi\Response;


abstract class BaseResponse implements Response
{
	/** @var mixed[] */
	private $rawData;


	/**
	 * @return mixed[]
	 */
	public function getRawData(): array
	{
		return $this->rawData;
	}


	/**
	 * @param mixed[] $rawData
	 */
	public function setRawData(array $rawData): void
	{
		$this->rawData = $rawData;
	}
}