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
	 *  @var string | null
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

	/**
	 * @param string $method
	 * @param Psr\Http\Message\UriInterface $uri
	 * @param array $headers
	 * @param Psr\Http\Message\StreamInterface | null $body
	 * @param string $protocolVersion
	 * */
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

    /**
	 * @{inheritdoc}
	 * */

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

	/**
	 * @param string $target 
	 **/
	protected function setRequestTarget($target)
	{

		$this->requestTarget = $target;

		return $this;
	}

	/**
	 * @{inheritdoc}
	 * */
	public function withRequestTarget($target)
	{
		$clone = clone $this;

		return $clone->setRequestTarget($target);
	}

	/**
	 * @{inheritdoc}
	 * */
	public function getMethod()
	{
		return $this->method;
	}


	/**
	 * Sets the method of request
	 * 
	 * @param string $method
	 * @return self
	 * */
	protected function setMethod($method)
	{
		$this->method = $method;

		return $this;
	}

	/**
	 * @{inheritdoc}
	 * */
	public function withMethod($method)
	{
		$clone = clone $this;

		return $clone->setMethod($method);
	}

	/**
	 * @{inheritdoc}
	 * */
	public function getUri()
	{
		return $this->uri;
	}

	/**
	 * Sets the UriInterface
	 * 
	 * @param UriInterface $uri
	 * @return self
	 * */
	protected function setUri(UriInterface $uri)
	{
		$this->uri = $uri;

		return $this;
	}

	/**
	 * @{inheritdoc}
	 * */
	public function withUri(UriInterface $uri, $preserveHost = false)
	{
		$clone = clone $this;

		if (! $preserveHost && $host = $uri->getHost()) {

			$clone->setHeader('host', $host);
		}

		return $clone->setUri($uri);
	}

}