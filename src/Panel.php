<?php

declare(strict_types=1);

namespace Baraja\HeurekaBiddingApi;


use Tracy\Dumper;
use Tracy\IBarPanel;

final class Panel implements IBarPanel
{

	/** @var string */
	private $accessKey;

	/** @var mixed[][] */
	private $calls = [];

	/** @var string[] */
	private $endpoints = [];


	public function __construct(string $accessKey)
	{
		$this->accessKey = $accessKey;
	}


	public function getPanel(): string
	{
		return '<h1 title="Heureka">'
			. 'Heureka [' . ($this->calls === [] ? 'offline' : 'connected') . ']'
			. '</h1>'
			. ($this->calls !== [] ? '<p>Connected with key <span style="background:#eee;white-space:nowrap">' . $this->accessKey . '</span>' : '')
			. ($this->calls === [] ? '<p>No calls.</p>'
				: '<div class="tracy-inner"><div class="tracy-inner-container">'
				. '<table style="margin-top:8px;width:100%">'
				. '<tr><th>Locale</th><th>Method</th><th>Params</th><th>Result</th></tr>'
				. (static function (array $calls): string {
					$return = [];
					foreach ($calls as $call) {
						$return[] = '<tr>'
							. '<td>' . htmlspecialchars($call['locale']) . '</td>'
							. '<td>' . htmlspecialchars($call['method']) . '</td>'
							. '<td>' . Dumper::toHtml($call['params']) . '</td>'
							. '<td>' . Dumper::toHtml($call['result']) . '</td>'
							. '</tr>';
					}

					return implode("\n", $return);
				})($this->calls)
				. '</table>'
				. '<p>Endpoints:</p>'
				. '<table>'
				. '<tr><th>Locale</th><th>URL</th></tr>'
				. (static function (array $endpoints): string {
					$return = [];
					foreach ($endpoints as $locale => $url) {
						$return[] = '<tr>'
							. '<td>' . htmlspecialchars($locale) . '</td>'
							. '<td>' . htmlspecialchars($url) . '</td>'
							. '</tr>';
					}

					return implode("\n", $return);
				})($this->endpoints)
				. '</table>'
				. '</div></div>');
	}


	public function getTab(): string
	{
		return '<span title="Heureka">'
			. '<img alt="Heureka" style="height:16px !important" src="data:image/png;base64,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">'
			. '</span>';
	}


	/**
	 * @internal
	 * @param string $endpoint
	 * @param string $locale
	 * @param string $method
	 * @param mixed[] $params
	 * @param mixed[] $result
	 */
	public function addCall(string $endpoint, string $locale, string $method, array $params, array $result): void
	{
		$this->endpoints[$locale] = $endpoint;
		$this->calls[] = [
			'locale' => $locale,
			'method' => $method,
			'params' => $params,
			'result' => $result,
		];
	}
}
