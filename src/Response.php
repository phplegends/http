<?php

namespace PHPLegends\Http;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class Response extends Message implements ResponseInterface
{
	
    /**
    * @var
    */
    protected $phrases = [
        100 => 'Continue',
        101 => 'Switching Protocols',
        102 => 'Processing',
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        207 => 'Multi-status',
        208 => 'Already Reported',
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        306 => 'Switch Proxy',
        307 => 'Temporary Redirect',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Time-out',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Large',
        415 => 'Unsupported Media Type',
        416 => 'Requested range not satisfiable',
        417 => 'Expectation Failed',
        418 => 'I\'m a teapot',
        422 => 'Unprocessable Entity',
        423 => 'Locked',
        424 => 'Failed Dependency',
        425 => 'Unordered Collection',
        426 => 'Upgrade Required',
        428 => 'Precondition Required',
        429 => 'Too Many Requests',
        431 => 'Request Header Fields Too Large',
        451 => 'Unavailable For Legal Reasons',
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Time-out',
        505 => 'HTTP Version not supported',
        506 => 'Variant Also Negotiates',
        507 => 'Insufficient Storage',
        508 => 'Loop Detected',
        511 => 'Network Authentication Required',
    ];
	/**
	 * 
	 * @var string
	 * */
	protected $reasonPhrase = null;

	/**
	 * @var int
	 * */
	protected $statusCode = 200;


	public function __construct($body, $code = 200, array $headers = [])
	{
		if (! $body instanceof StreamInterface) {            

            $body = Stream::createFromString($body);
		}

        $this->setHeader('Content-Type', 'text/html; charset=utf-8;');
        
        parent::__construct($body, $headers);
	}

    /**
     * Gets the value of reasonPhrase.
     *
     * @return Psr\Http\Message\StreamInterface
     */
    public function getReasonPhrase()
    {
        $code = $this->getStatusCode();

        if ($this->reasonPhrase === null && isset($this->phrases[$code]))
        {
            return $this->phrases[$code];
        }

        return (string) $this->reasonPhrase;
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

    public function send()
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

        header(sprintf(
            'HTTP/%s %s %s',
            $this->getProtocolVersion(),
            $this->getStatusCode(),
            $this->getReasonPhrase()
        ));

        foreach ($this->getHeaderKeys() as $name)
        {
            header(sprintf('%s: %s', $name, $this->getHeaderLine($name)), false);
        }

        return true;
    }
}
