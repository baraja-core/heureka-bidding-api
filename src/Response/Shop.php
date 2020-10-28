<?php

declare(strict_types=1);

namespace Baraja\HeurekaBiddingApi\Response;


final class Shop extends BaseResponse
{
	private int $id;

	private string $slug;

	private string $name;

	private string $homepage;

	/** @var mixed[]|null */
	private ?array $verifiedByCustomersStatus;

	private bool $certifiedSeller;

	/** @var mixed|null */
	private $shopOfTheYear;

	private ?int $rating;

	private ?int $ratingCount;

	private ?int $reviewCount;

	private bool $cashBackQuarantee;


	/**
	 * @param mixed[] $haystack
	 */
	public function __construct(array $haystack)
	{
		$this->id = $haystack['id'];
		$this->slug = $haystack['slug'];
		$this->name = $haystack['name'];
		$this->homepage = $haystack['homepage'];
		$this->verifiedByCustomersStatus = $haystack['verified_by_customers_status'];
		$this->certifiedSeller = $haystack['is_certified_seller'];
		$this->shopOfTheYear = $haystack['shop_of_the_year'];
		$this->rating = $haystack['rating'];
		$this->ratingCount = $haystack['rating_count'];
		$this->reviewCount = $haystack['review_count'];
		$this->cashBackQuarantee = $haystack['has_cashback_guarantee'];
	}


	public function getId(): int
	{
		return $this->id;
	}


	public function getSlug(): string
	{
		return $this->slug;
	}


	public function getName(): string
	{
		return $this->name;
	}


	public function getHomepage(): string
	{
		return $this->homepage;
	}


	/**
	 * @return mixed[]|null
	 */
	public function getVerifiedByCustomersStatus(): ?array
	{
		return $this->verifiedByCustomersStatus;
	}


	public function isCertifiedSeller(): bool
	{
		return $this->certifiedSeller;
	}


	/**
	 * @return mixed|null
	 */
	public function getShopOfTheYear()
	{
		return $this->shopOfTheYear;
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


	public function isCashBackQuarantee(): bool
	{
		return $this->cashBackQuarantee;
	}
}
