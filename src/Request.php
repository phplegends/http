<?php

namespace PHPLegends\Http;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;

/**
 * @author Wallace de Souza Vizerra <wallacemaxters@gmail.com>
 * */
class Request extends Message implements RequestInterface
{

	/**
	 *  
	 **/
	protected $requestTarget;

	/**
	 * @var Psr\Http\Message\UriInterface
	 * */
	protected $uri;

	/**
	 * Method of request
	 * 
	 * @var string
	 * */
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
		if ($this->requestTarget) {

			return $this->requestTarget;
		}

		$target = rtrim($this->getUri()->getPath(), '/') . '/';

		if ($query = $this->getUri()->getQuery()) {

			$target .= '?' . $query;
		}

		return $target;

	}

	protected function setRequestTarget($target)
	{

		$this->requestTarget = $target;

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

	protected function setMethod($method)
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

	protected function setUri(UriInterface $uri)
	{
		$this->uri = $uri;

		return $this;
	}

	public function withUri(UriInterface $uri, $preserveHost = false)
	{
		$clone = clone $this;

		if (! $preserveHost)
		{
			// Corrigir essa parada aqui
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