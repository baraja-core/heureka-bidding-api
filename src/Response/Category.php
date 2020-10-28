<?php

declare(strict_types=1);

namespace Baraja\HeurekaBiddingApi\Response;


final class Category extends BaseResponse
{
	private int $id;

	private ?int $parentId;

	private string $name;

	private string $slug;

	private bool $leaf;

	private ?int $productCount;

	private string $url;


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


	public function getId(): int
	{
		return $this->id;
	}


	public function getParentId(): ?int
	{
		return $this->parentId;
	}


	public function getName(): string
	{
		return $this->name;
	}


	public function getSlug(): string
	{
		return $this->slug;
	}


	public function isLeaf(): bool
	{
		return $this->leaf;
	}


	public function getProductCount(): ?int
	{
		return $this->productCount;
	}


	public function getUrl(): string
	{
		return $this->url;
	}
}
