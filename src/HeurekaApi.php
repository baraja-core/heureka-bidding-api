<?php

declare(strict_types=1);

namespace Baraja\HeurekaBiddingApi;


use Baraja\HeurekaBiddingApi\Response\AccessRateIndexResponse;
use Baraja\HeurekaBiddingApi\Response\Category;
use Baraja\HeurekaBiddingApi\Response\CategoryIndexResponse;
use Baraja\HeurekaBiddingApi\Response\Product;
use Baraja\HeurekaBiddingApi\Response\ProductIndexResponse;
use Baraja\HeurekaBiddingApi\Response\Response;

final class HeurekaApi
{
	public const ENDPOINT_CZ = 'https://api.heureka.cz/bidding_api/v1';

	public const ENDPOINT_SK = 'https://api.heureka.sk/bidding_api/v1';

	public const METHOD_ACCESS_RATE_INDEX = 'access_rate.index';

	public const METHOD_CATEGORY_INDEX = 'category.index';

	public const METHOD_CATEGORY_GET = 'category.get';

	public const METHOD_PRODUCT_INDEX = 'product.index';

	public const METHOD_PRODUCT_GET = 'product.get';

	public const METHODS = [
		self::METHOD_ACCESS_RATE_INDEX,
		self::METHOD_CATEGORY_INDEX,
		self::METHOD_CATEGORY_GET,
		self::METHOD_PRODUCT_INDEX,
		self::METHOD_PRODUCT_GET,
	];

	/** @var string[] (locale => URL) */
	private $customEndpoints = [];

	/** @var string */
	private $accessKey;


	/**
	 * @param string $accessKey
	 */
	public function __construct(string $accessKey)
	{
		$this->accessKey = $accessKey;
	}


	/**
	 * Call internal API endpoint and rewrite response to PHP entity with data types.
	 *
	 * @param string $method by METHOD_* constant or documentation https://api.heureka.cz/bidding_api/v1/apidoc
	 * @param mixed[] $params (some parameters should be required)
	 * @param string $locale ("cs" or "sk"), shortcut "cz" is too supported.
	 * @return Response (method return specific final entity)
	 * @throws HeurekaException
	 */
	public function run(string $method, array $params = [], string $locale = 'cs'): Response
	{
		return $this->mapResponseToObject($method, $this->processRawResponse($this->resolveEndpoint(strtolower($locale)), $locale, $method, $params));
	}


	/**
	 * The given endpoint will be used as the master for routing all requests.
	 *
	 * @internal
	 * @param string $locale
	 * @param string $endpoint
	 */
	public function setCustomEndpoint(string $locale, string $endpoint): void
	{
		$this->customEndpoints[strtolower($locale)] = $endpoint;
	}


	/**
	 * @param string $locale
	 * @return string
	 * @throws HeurekaException
	 */
	public function resolveEndpoint(string $locale): string
	{
		if (isset($this->customEndpoints[$locale]) === true) {
			return $this->customEndpoints[$locale];
		}

		if ($locale === 'cz' || $locale === 'cs') {
			return self::ENDPOINT_CZ;
		}

		if ($locale === 'sk') {
			return self::ENDPOINT_SK;
		}

		throw HeurekaException::apiEndpointDoesNotExist($locale);
	}


	/**
	 * @param string $endpoint
	 * @param string $locale
	 * @param string $method
	 * @param mixed[] $params
	 * @return mixed[]
	 * @throws HeurekaException
	 */
	public function processRawResponse(string $endpoint, string $locale, string $method, array $params = []): array
	{
		if (\in_array($method, self::METHODS, true) === false) {
			HeurekaException::methodDoesNotExist($method);
		}

		$body = [
			'jsonrpc' => '2.0',
			'id' => '1',
			'method' => $method,
			'params' => array_merge([
				'language' => $locale === 'cs' ? 'cz' : $locale,
				'access_key' => $this->accessKey,
			], $params),
		];

		if (function_exists('curl_version') === true) {
			$result = $this->callByCurl($endpoint, $body);
		} else {
			$result = $this->callByFileGetContents($endpoint, $body);
		}

		if (isset($result['error']) === true) {
			HeurekaException::apiRuntimeError($result['error']['message'] ?? '', (int) ($result['error']['code'] ?? 500));
		}

		return $result;
	}


	/**
	 * @param string $haystack
	 * @return mixed[]
	 */
	private function jsonDecode(string $haystack): array
	{
		if ($haystack === '') {
			throw new \RuntimeException('Empty haystack.');
		}

		$value = json_decode($haystack, true, 512, JSON_BIGINT_AS_STRING);

		if ($error = json_last_error()) {
			throw new \RuntimeException(json_last_error_msg(), $error);
		}

		return $value;
	}


	/**
	 * @param string $url
	 * @param mixed[] $body
	 * @return mixed[]
	 * @throws HeurekaException
	 */
	private function callByCurl(string $url, array $body): array
	{
		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_HEADER, false);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-type: application/json']);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($body));
		$parsedResponse = $this->jsonDecode($rawResponse = curl_exec($curl));
		$status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

		if ((int) $status !== 200) {
			throw new HeurekaException(
				'Error: call to URL "' . $url . '", (curl_error: ' . curl_error($curl) . ', curl_errno: ' . curl_errno($curl) . ')'
				. ' failed with status: ' . $status . "\n\n" . 'Response: ' . $rawResponse
			);
		}

		curl_close($curl);

		return $parsedResponse;
	}


	/**
	 * PHP native implementation for backward support.
	 *
	 * @param string $url
	 * @param mixed[] $body
	 * @return mixed[]
	 */
	private function callByFileGetContents(string $url, array $body): array
	{
		return $this->jsonDecode(file_get_contents($url, false, stream_context_create([
			'http' => [
				'method' => 'POST',
				'header' => 'Content-type: application/json',
				'user_agent' => 'BarajaBot in PHP',
				'content' => json_encode($body),
			],
		])));
	}


	/**
	 * @param string $method
	 * @param mixed[] $rawData
	 * @return Response
	 */
	private function mapResponseToObject(string $method, array $rawData): Response
	{
		$result = $rawData['result'];

		if ($method === self::METHOD_ACCESS_RATE_INDEX) {
			$return = new AccessRateIndexResponse($result['count'], $result['access_rates']);
		} elseif ($method === self::METHOD_CATEGORY_INDEX) {
			$return = new CategoryIndexResponse($result['count'], $result['categories']);
		} elseif ($method === self::METHOD_CATEGORY_GET) {
			$return = new Category($result['category']);
		} elseif ($method === self::METHOD_PRODUCT_INDEX) {
			$return = new ProductIndexResponse($result['count'], $result['products']);
		} elseif ($method === self::METHOD_PRODUCT_GET) {
			$return = new Product($result['product']);
		} else {
			throw new \RuntimeException('Mapper for method "' . $method . '" does not exist.');
		}

		$return->setRawData($rawData);

		return $return;
	}
}