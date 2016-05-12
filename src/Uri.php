<?php

namespace PHPLegends\Http;

use Psr\Http\Message\UriInterface;

class Uri implements UriInterface
{
	public function __construct($uri)
	{
		$this->uri = $uri;
	}

	public function getQuery()
	{
		return parse_url($this->uri, PHP_URL_QUERY);
	}
}