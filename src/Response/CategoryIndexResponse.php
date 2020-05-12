<?php

declare(strict_types=1);

namespace Baraja\HeurekaBiddingApi\Response;


final class CategoryIndexResponse extends BaseResponse
{
	/** @var int */
	private $count;

	/** @var Category[] */
	private $categories;


	/**
	 * @param int $count
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


	/**
	 * @return int
	 */
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