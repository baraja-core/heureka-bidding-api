<?php

declare(strict_types=1);

namespace Baraja\HeurekaBiddingApi;


final class HeurekaException extends \Exception
{

	/**
	 * @param string $method
	 * @throws HeurekaException
	 */
	public static function methodDoesNotExist(string $method): void
	{
		throw new self('API method "' . $method . '" does not exist. Did you mean "' . implode('", "', HeurekaApi::METHODS) . '"?');
	}


	/**
	 * @param string $message
	 * @param int $code
	 * @throws HeurekaException
	 */
	public static function apiRuntimeError(string $message, int $code): void
	{
		if ($message === 'Missing or invalid access key.') {
			$message .= ' Did you ask the Heureka administrators for an access key? More information: https://sluzby.heureka.cz/napoveda/bidding-api/';
		}

		if (preg_match('/^Method "([^"]+)" is not defined\.$/', $message)) {
			$message .= ' Did you mean "' . implode('", "', HeurekaApi::METHODS) . '"?';
		}

		throw new self('API runtime error: ' . $message, abs($code));
	}


	/**
	 * @param string $locale
	 * @return HeurekaException
	 */
	public static function apiEndpointDoesNotExist(string $locale): self
	{
		return new self('API endpoint for locale "' . $locale . '" does not exist. Did you mean "cs" or "sk"?');
	}
}