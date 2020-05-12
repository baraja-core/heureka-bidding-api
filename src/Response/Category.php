<?php

declare(strict_types=1);

namespace Baraja\HeurekaBiddingApi\Response;


final class Category extends BaseResponse
{
	/** @var int */
	private $id;

	/** @var int|null */
	private $parentId;

	/** @var string */
	private $name;

	/** @var string */
	private $slug;

	/** @var bool */
	private $leaf;

	/** @var int|null */
	private $productCount;

	/** @var string */
	private $url;


	/**
	 * @param mixed[] $haystack
	 */
	public function __construct(array $haystack)
	{
		$this->id = $haystack['id'];
		$this->parentId = $haystack['parent_id'] ?? null;
		$this->name = $haystack['name'];
		$this->slug = $haystack['slug'];
		$this->leaf = $haystack['is_leaf'] ?? false;
		$this->productCount = $haystack['product_count'] ?? null;
		$this->url = $haystack['url'];
	}


	/**
	 * @return int
	 */
	public function getId(): int
	{
		return $this->id;
	}


	/**
	 * @return int|null
	 */
	public function getParentId(): ?int
	{
		return $this->parentId;
	}


	/**
	 * @return string
	 */
	public function getName(): string
	{
		return $this->name;
	}


	/**
	 * @return string
	 */
	public function getSlug(): string
	{
		return $this->slug;
	}


	/**
	 * @return bool
	 */
	public function isLeaf(): bool
	{
		return $this->leaf;
	}


	/**
	 * @return int|null
	 */
	public function getProductCount(): ?int
	{
		return $this->productCount;
	}


	/**
	 * @return string
	 */
	public function getUrl(): string
	{
		return $this->url;
	}
}