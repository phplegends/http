<?php

namespace PHPLegends\Http;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;

class Request extends Message implements RequestInterface
{

	protected $target;

	protected $uri;

	protected $method = 'GET';

    public function __construct (
    	$method,
    	UriInterface $uri,
    	array $headers = [],
    	StreamInterface $body = null,
    	$protocolVersion = '1.1'
    ) {

      	parent::__construct($body, $headers);

      	$this->setMethod($method)
        	  ->setUri($uri)
        	  ->setProtocolVersion($protocolVersion);
    }

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
		$clone = clone $this;

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
		$clone = clone $this;

		if (! $preserveHost)
		{
			$clone->removeAllHeaders();
		}

		return $clone->setUri($uri);
	}

    /**
     * Gets the value of target.
     *
     * @return mixed
     */
    public function getTarget()
    {
        return $this->target;
    }

    /**
     * Sets the value of target.
     *
     * @param mixed $target the target
     *
     * @return self
     */
    protected function setTarget($target)
    {
        $this->target = $target;

        return $this;
    }

}