<?php

declare(strict_types=1);

namespace Baraja\HeurekaBiddingApi\Response;


final class CategoryIndexResponse extends BaseResponse
{
	private int $count;

	/** @var Category[] */
	private array $categories;


	/**
	 * @param mixed[] $categories
	 */
	public function __construct(int $count, array $categories)
	{
		$categoryEntities = [];
		foreach ($categories as $category) {
			$categoryEntity = new Category($category);
			$categoryEntity->setRawData($category);
			$categoryEntities[] = $categoryEntity;
		}

		$this->count = $count;
		$this->categories = $categoryEntities;
	}


	public function getCount(): int
	{
		return $this->count;
	}


	/**
	 * @return Category[]
	 */
	public function getCategories(): array
	{
		return $this->categories;
	}
}
