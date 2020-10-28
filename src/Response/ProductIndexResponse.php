<?php

declare(strict_types=1);

namespace Baraja\HeurekaBiddingApi\Response;


final class ProductIndexResponse extends BaseResponse
{
	private int $count;

	/** @var ProductResult[] */
	private array $products;


	/**
	 * @param mixed[] $products
	 */
	public function __construct(int $count, array $products)
	{
		$productEntities = [];
		foreach ($products as $product) {
			$productEntity = new ProductResult($product);
			$productEntity->setRawData($product);
			$productEntities[] = $productEntity;
		}

		$this->count = $count;
		$this->products = $productEntities;
	}


	public function getCount(): int
	{
		return $this->count;
	}


	/**
	 * @return ProductResult[]
	 */
	public function getProducts(): array
	{
		return $this->products;
	}
}
