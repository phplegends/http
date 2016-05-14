<?php

namespace PHPLegends\Http;

use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\StreamInterface;

class Message implements MessageInterface
{

	/**
	 * 
	 * @var Psr\Http\Message\StreamInterface
	 * */
	protected $body;

	/**
	 * @var array
	 * */
	protected $headers = [];

	/**
	 * @var string
	 * */
	protected $protocolVersion = '1.1';


	public function __construct(StreamInterface $body = null, array $headers = [])
	{
		$this->body = $body;

		$this->setHeaders($headers);
	}

	/**
	 * @{inheritdoc}
	 */
	public function getProtocolVersion()
	{
	    return $this->protocolVersion;
	}

	/**
	 * Sets the value of protocolVersion.
	 *
	 * @param string|float $protocolVersion the protocol version
	 *
	 * @return self
	 */
	protected function setProtocolVersion($protocolVersion)
	{
	    $this->protocolVersion = (string) $protocolVersion;

	    return $this;
	}

	/**
	 * @{inheritdoc}
	 */
	public function getHeaders()
	{
	    return $this->headers;
	}

	/**
	 * Sets the value of headers.
	 *
	 * @param array $headers the headers
	 *
	 * @return self
	 */
	protected function setHeaders(array $headers)
	{
	    foreach ($headers as $name => $value) {
	    	
	    	$this->setHeader($name, $value);
	    }

	    return $this;
	}

	protected function setHeader($name, $value)
	{
		$name = $this->normalizeHeaderName($name);

		$this->headers[$name] = (array) $value;

		return $this;
	}

	/**
	 * Remove a item of header
	 * 
	 * @return  array | false
	 * */
	protected function removeHeader($name)
	{
		if (! $this->hasHeader($name))
		{
			return false;
		}	

		$value = $this->getHeader($name);

		unset($this->headers[$name]);

		return $value;
	}

	/**
	 * @{inheritdoc}
	 */
	public function getHeader($name)
	{
		$name = $this->normalizeHeaderName($name);

		$headers = $this->headers + [$name => []];

		return $headers[$name];
	}

	/**
	 * @{inheritdoc}
	 */
	public function hasHeader($name)
	{
		return isset($this->headers[$this->normalizeHeaderName($name)]);
	}

	protected function removeAllHeaders()
	{
		$this->headers = [];

		return $this;
	}

	protected function mergeHeader($name, $value)
	{
		if ($this->hasHeader($name))
		{
			$header = array_unique(array_merge((array) $value, $this->getHeader($name)));

			return $this->setHeader($name, $headers);
		}

		return $this->setHeader($name, $value);
	}

	/**
	 * @{inheritdoc}
	 */
	public function withProtocolVersion($version)
	{
		$clone = clone $this;

		return $clone->setProtocolVersion($version);
	}

	/**
	 * @{inheritdoc}
	 */
	public function withHeader($name, $value)
	{
		$clone = clone $this;

		return $clone->setHeader($name, $value);
	}

	/**
	 * @{inheritdoc}
	 */
	public function withoutHeader($name)
	{
		$clone = clone $this;

		return $clone->removeHeader($name);
	}

	/**
	 * @{inheritdoc}
	 */
	public function getHeaderLine($name)
	{
		if ($this->hasHeader($name))
		{
			return implode(', ', $this->getHeader($name));
		}

		return '';
	}

	/**
	 * @{inheritdoc}
	 */
	public function withAddedHeader($name, $value)
	{
		$clone = clone $this;

		return $clone->mergeHeader($name, $value);
	}

	/**
	 * @{inheritdoc}
	 */

	public function withBody(StreamInterface $body)
	{
		$clone = clone $this;

		return $clone->setBody($body);
	}

	/**
	 * Gets the value of body.
	 *
	 * @return StreamInterface
	 */
	public function getBody()
	{
	    return $this->body;
	}

	/**
	 * Sets the value of body.
	 *
	 * @param mixed $body the body
	 *
	 * @return self
	 */
	protected function setBody(StreamInterface $body)
	{
	    $this->body = $body;

	    return $this;
	}

	protected static function normalizeHeaderName($name)
	{
		return mb_convert_case($name, MB_CASE_TITLE);
	}
}