<?php

declare(strict_types=1);

namespace Baraja\HeurekaBiddingApi\Response;


final class Product extends BaseResponse
{
	private int $id;

	private string $name;

	private string $slug;

	private string $url;

	private Category $category;

	private int $categoryPosition;

	private float $minPrice;

	private int $statusId;

	private string $statusName;

	private ?int $rating;

	private ?int $ratingCount;

	private ?int $reviewCount;

	private ?int $producerId;

	private ?string $producerName;

	/** @var Shop[] */
	private array $shops = [];

	private ?Shop $topShop;

	/** @var Shop[] */
	private array $highlightedShops = [];

	/** @var mixed[] */
	private array $offerAttributes;

	/** @var string[][] */
	private array $images;


	/**
	 * @param mixed[] $haystack
	 */
	public function __construct(array $haystack)
	{
		$this->id = $haystack['id'];
		$this->name = $haystack['name'];
		$this->slug = $haystack['slug'];
		$this->url = $haystack['url'];
		$this->category = new Category($haystack['category']);
		$this->categoryPosition = $haystack['category_position'] ?? 0;
		$this->minPrice = (float) $haystack['min_price'];
		$this->statusId = $haystack['status']['id'];
		$this->statusName = $haystack['status']['name'];

		if (isset($haystack['rating']) === true) {
			$this->rating = $haystack['rating']['rating'];
			$this->ratingCount = $haystack['rating']['rating_count'];
			$this->reviewCount = $haystack['rating']['review_count'];
		}
		if (isset($haystack['producer']) === true) {
			$this->producerId = $haystack['producer']['id'];
			$this->producerName = $haystack['producer']['name'];
		}
		if (isset($haystack['top_shop']) === true) {
			$this->topShop = new Shop($haystack['top_shop']);
		}
		if (isset($haystack['highlighted_shops']) !== []) {
			$highlightedShops = [];
			foreach ($haystack['highlighted_shops'] as $highlightedShop) {
				$highlightedShops[] = new Shop($highlightedShop);
			}
			$this->highlightedShops = $highlightedShops;
		}
		if (isset($haystack['shops']) !== []) {
			$shops = [];
			foreach ($haystack['shops'] as $shop) {
				$shops[] = new Shop($shop);
			}
			$this->shops = $shops;
		}

		$this->offerAttributes = $haystack['offer_attributes'] ?? [];
		$this->images = $haystack['images'] ?? [];
	}


	public function getId(): int
	{
		return $this->id;
	}


	public function getName(): string
	{
		return $this->name;
	}


	public function getSlug(): string
	{
		return $this->slug;
	}


	public function getUrl(): string
	{
		return $this->url;
	}


	public function getCategory(): Category
	{
		return $this->category;
	}


	public function getCategoryPosition(): int
	{
		return $this->categoryPosition;
	}


	public function getMinPrice(): float
	{
		return $this->minPrice;
	}


	public function getStatusId(): int
	{
		return $this->statusId;
	}


	public function getStatusName(): string
	{
		return $this->statusName;
	}


	public function isSoldOut(): bool
	{
		return $this->statusName !== 'ACTIVE';
	}


	public function getRating(): ?int
	{
		return $this->rating;
	}


	public function getRatingCount(): ?int
	{
		return $this->ratingCount;
	}


	public function getReviewCount(): ?int
	{
		return $this->reviewCount;
	}


	public function getProducerId(): ?int
	{
		return $this->producerId;
	}


	public function getProducerName(): ?string
	{
		return $this->producerName;
	}


	/**
	 * @return Shop[]
	 */
	public function getShops(): array
	{
		return $this->shops;
	}


	public function getTopShop(): ?Shop
	{
		return $this->topShop;
	}


	public function getShopsCount(): int
	{
		return \count($this->shops);
	}


	/**
	 * @return Shop[]
	 */
	public function getHighlightedShops(): array
	{
		return $this->highlightedShops;
	}


	/**
	 * @return mixed[]
	 */
	public function getOfferAttributes(): array
	{
		return $this->offerAttributes;
	}


	/**
	 * @return string[][]
	 */
	public function getImages(): array
	{
		return $this->images;
	}


	public function getMainImageUrl(): ?string
	{
		return $this->images[0]['thumbnail'] ?? null;
	}
}
