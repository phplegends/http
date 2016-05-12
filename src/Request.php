<?php

namespace PHPLegends\Http;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class Request extends Message implements ResponseInterface
{

	protected $target;

	protected $uri;

	protected $method;

	public function getRequestTarget()
	{
		return $this->target;
	}

	public function setRequestTarget($target)
	{
		$this->target = $target;

		return $this;
	}

	public function withRequestTarget($target)
	{
		$clone = clone $this;

		return $clone->setRequestTarget($target);
	}

	public function getMethod()
	{
		return $this->method;
	}

	public function setMethod($method)
	{
		$this->method = $method;

		return $this;
	}

	public function withMethod($method)
	{
		$clone = $this;

		return $clone->setMethod($method);
	}

	public function getUri()
	{
		return $this->uri;
	}

	public function setUri(UriInterface $uri)
	{
		$this->uri = $uri;

		return $this;
	}

	public function withUri(UriInterface $uri, $preserveHost = false)
	{
		$clone = $this;

		if (! $preserveHost)
		{
			$clone->removeAllHeaders();
		}

		return $clone->setUri($uri);
	}
}