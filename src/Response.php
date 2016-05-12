<?php

namespace PHPLegends\Http;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class Response extends Message implements ResponseInterface
{
	

	/**
	 * 
	 * @var string
	 * */
	protected $reasonPhrase = '';

	/**
	 * @var int
	 * */
	protected $statusCode = 200;


	public function __construct($body)
	{
		if (! $body instanceof StreamInterface) {            

            $body = Stream::createFromString($body);
		}

        parent::__construct($body);

        $this->setHeader('Content-Type', 'text/html; charset=utf-8;');
	}

    /**
     * Gets the value of reasonPhrase.
     *
     * @return Psr\Http\Message\StreamInterface
     */
    public function getReasonPhrase()
    {
        return $this->reasonPhrase;
    }

    /**
     * Sets the value of reasonPhrase.
     *
     * @param mixed $reasonPhrase the reason phrase
     *
     * @return self
     */
    protected function setReasonPhrase($reasonPhrase)
    {
        $this->reasonPhrase = $reasonPhrase;

        return $this;
    }

    /**
     * Gets the value of statusCode.
     *
     * @return mixed
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * Sets the value of statusCode.
     *
     * @param int $statusCode the status code
     *
     * @return self
     */
    public function setStatusCode($statusCode)
    {
    	if (! is_int($statusCode))
    	{
    		throw new \InvalidArgumentException('The statusCode MUST BE integer');
    	}

        $this->statusCode = $statusCode;

        return $this;
    }


    public function withStatus($code, $reasonPhrase  = '')
    {
        $clone = clone $this;

        return $clone->setStatusCode($code)
                     ->setReasonPhrase($reasonPhrase);
    }

    public function render()
    {
        $this->sendHeaders();

        echo $this->getBody()->getContents();
    }

    /**
     * 
     * @return void
     * */
    // public function __clone()
    // {
    //     if (($body = $this->getBody()) instanceof StreamInterface)
    //     {
    //         $this->body = clone $body;
    //     }
    // }


    public function getHeaderKeys()
    {
        return array_keys($this->getHeaders());
    }


    /**
     * Process all headers. Return true if headers are send, and false if are sent
     * 
     * @return boolean
     * */
    protected function sendHeaders()
    {
        if (headers_sent()) return false;

        foreach ($this->getHeaderKeys() as $name)
        {
            header($this->getHeaderLine($name), false);
        }

        return true;
    }
}
