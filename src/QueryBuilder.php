<?php

declare(strict_types=1);

namespace Baraja\HeurekaBiddingApi;


use Baraja\HeurekaBiddingApi\Response\AccessRate;
use Baraja\HeurekaBiddingApi\Response\Category;
use Baraja\HeurekaBiddingApi\Response\CategoryIndexResponse;
use Baraja\HeurekaBiddingApi\Response\Product;
use Baraja\HeurekaBiddingApi\Response\ProductIndexResponse;
use Nette\Utils\DateTime;

final class QueryBuilder
{
	private HeurekaApi $heurekaApi;

	private string $locale;


	public function __construct(HeurekaApi $heurekaApi, string $locale)
	{
		$this->heurekaApi = $heurekaApi;
		$this->locale = $locale;
	}


	/**
	 * Returns list of records about BiddingAPI usage by the authenticated user.
	 *
	 * @throws HeurekaException
	 */
	public function accessRateIndex(int $limit = 1000, int $offset = 0, string $type = 'month', ?\DateTime $from = null, ?\DateTime $to = null, ?string $order = null): AccessRate
	{
		if ($limit < 1) {
			$limit = 1;
		} elseif ($limit > 10000) {
			$limit = 10000;
		}
		if ($offset < 0) {
			$offset = 0;
		}

		$from = $from ?? DateTime::from('now - 1 month');
		$to = $to ?? DateTime::from('now');

		if ($type === 'month') { // YYYY-MM
			$fromString = $from->format('Y-m');
			$toString = $to->format('Y-m');
		} elseif ($type === 'minute') { // YYYY-MM-DD HH:MM
			$fromString = $from->format('Y-m-d H:i');
			$toString = $to->format('Y-m-d H:i');
		} else {
			throw new \InvalidArgumentException('Type "' . $type . '" does not exist. Did you mean "month" or "minute"?');
		}

		/** @var AccessRate $return */
		$return = $this->heurekaApi->run(HeurekaApi::METHOD_ACCESS_RATE_INDEX, [
			'limit' => $limit,
			'offset' => $offset,
			'type' => $type,
			'from' => $fromString,
			'to' => $toString,
			'order' => $order ?? 'slot:asc',
		], $this->locale);

		return $return;
	}


	/**
	 * Returns list of all visible categories
	 *
	 * @throws HeurekaException
	 */
	public function categoryIndex(): CategoryIndexResponse
	{
		/** @var CategoryIndexResponse $return */
		$return = $this->heurekaApi->run(HeurekaApi::METHOD_CATEGORY_INDEX, [], $this->locale);

		return $return;
	}


	/**
	 * Get detailed info about a single category, its parent, children and top products.
	 *
	 * @param string $slug part of URL, for example "elektronika".
	 * @return Category
	 * @throws HeurekaException
	 */
	public function categoryGet(string $slug): Category
	{
		/** @var Category $return */
		$return = $this->heurekaApi->run(HeurekaApi::METHOD_CATEGORY_GET, [
			'id' => $slug,
		], $this->locale);

		return $return;
	}


	/**
	 * Search and list products.
	 *
	 * @throws HeurekaException
	 */
	public function productIndex(string $query, int $limit = 20, int $offset = 0): ProductIndexResponse
	{
		if (($query = trim($query)) === '') {
			throw new \InvalidArgumentException('Query can not be empty.');
		}
		if ($limit < 1) {
			$limit = 1;
		} elseif ($limit > 1000) {
			$limit = 1000;
		}
		if ($offset < 0) {
			$offset = 0;
		} elseif ($offset > 5000) {
			$offset = 5000;
		}

		/** @var ProductIndexResponse $return */
		$return = $this->heurekaApi->run(HeurekaApi::METHOD_PRODUCT_INDEX, [
			'query' => $query,
			'limit' => $limit,
			'offset' => $offset,
		], $this->locale);

		return $return;
	}


	/**
	 * Get detailed info about a single product and offers from individual shops.
	 *
	 * @param string|null $categoryId Slug of a product category. Recommended: use for disambiguation of product slugs as they might not be unique across categories.
	 * @param string[] $attributes (id => value)
	 * @return Product
	 * @throws HeurekaException
	 */
	public function productGet(string $slug, ?string $categoryId = null, array $attributes = []): Product
	{
		$returnAttributes = [];
		foreach ($attributes as $attributeKey => $attributeValue) {
			$returnAttributes[] = [
				'id' => (int) $attributeKey,
				'value' => $attributeValue,
			];
		}

		$params = ['id' => $slug];
		if ($categoryId !== null) {
			$params['category_id'] = $categoryId;
		}
		if ($returnAttributes !== []) {
			$params['attributes'] = $returnAttributes;
		}

		/** @var Product $return */
		$return = $this->heurekaApi->run(HeurekaApi::METHOD_PRODUCT_GET, $params, $this->locale);

		return $return;
	}
}
