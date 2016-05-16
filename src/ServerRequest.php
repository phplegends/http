<?php

namespace PHPLegends\Http;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UriInterface;
use Psr\Http\Message\StreamInterface;

/**
 * 
 * @author Wallace de Souza Vizerra <wallacemaxters@gmail.com>
 * 
 * */
class ServerRequest extends Request implements ServerRequestInterface
{
	protected $serverParams = [];

	protected $cookieParams = [];

	protected $queryParams = [];

	protected $uploadedFiles = [];

	protected $attributes = [];

    protected $parsedBody = [];


    /**
     * The object constructor ;)
     * 
     * @param string $method
     * @param \Psr\Http\Message\UriInterface $uri
     * @param array $headers
     * @param |Psr\Http\Message\StreamInterface | null $body
     * @param string $protocolVersion
     * @param array $serverParams
     * */
    public function __construct (
    	$method,
    	UriInterface $uri,
    	array $headers = [],
    	StreamInterface $body = null,
    	$protocolVersion = '1.1',
    	$serverParams = []
    ) {

    	parent::__construct($method, $uri, $headers, $body, $protocolVersion);

    	$this->serverParams = $serverParams;
    }

    /**
     * Gets the value of serverParams.
     *
     * @return mixed
     */
    public function getServerParams()
    {
        return $this->serverParams;
    }

    /**
     * Gets the value of cookieParams.
     *
     * @return mixed
     */
    public function getCookieParams()
    {
        return $this->cookieParams;
    }

    /**
     * Gets the value of queryParams.
     *
     * @return mixed
     */
    public function getQueryParams()
    {
        return $this->queryParams;
    }

    /**
     * Gets the value of uploadedFiles.
     *
     * @return mixed
     */
    public function getUploadedFiles()
    {
        return $this->uploadedFiles;
    }

    /**
     * Normalizes the value of uploadedFiles.
     *
     * @param mixed $uploadedFiles the uploaded files
     *
     * @return self
     */
    protected static function normalizeUploadedFiles(array $uploadedFiles)
    {
        $files = [];

        foreach ($uploadedFiles as $key => $value) {

            if ($value instanceof UploadedFileInterface) {

                $files[$key] = $value;

            } elseif (is_array($value) && isset($value['tmp_name'])) {

                $files[$key] = static::createUploadedFileFromSpec($value);

            } elseif (is_array($value)) {
                
                $files[$key]  = static::normalizeUploadedFiles($value);

            } else {

                throw new \InvalidArgumentException('Invalid uploaded files value');
            }
        }

        return $files;
    }

    protected static function createUploadedFileFromSpec(array $value)
    {
        if (is_array($value['tmp_name']))
        {
            return static::normalizeUploadedFiles($value);
        }

        return new UploadedFile(
            $value['tmp_name'], 
            $value['name'], 
            $value['size'],
            $value['error'],
            $value['type']
        );
    }

    /**
     * Gets the value of attributes.
     *
     * @return mixed
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    public function withCookieParams(array $cookies)
    {
        $clone = clone $this;

        $clone->cookieParams = $cookies;

        return $clone;
    }

    public function withQueryParams(array $params)
    {   
        $clone = clone $this;

        $clone->queryParams = $params;

        return $clone;
    }

    public function withUploadedFiles(array $files)
    {
        $clone = clone $this;

        $clone->uploadedFiles = $files;

        return $clone;
    }

    public function getParsedBody()
    {
        $body = $this->getBody()->rewind()->getContents();

        return $this->parsedBody;

    }

    public function withAttribute($name, $attribute)
    {
        $clone = clone $this;

        $clone->attributes[$name] = $attributes;

        return $clone;
    }

    public function withParsedBody($data)
    {
        $clone = clone $this;

        $clone->parsedBody = $data;

        return $clone;
    }

    public function withoutAttribute($name)
    {
        if (! isset($this->attributes[$name])) {

            return clone $this;
        }

        $clone = clone $this;

        unset($clone->attribute[$name]);

        return $clone;
    }

    public function getAttribute($name, $default = null)
    {
        if (isset($this->attributes[$name])) {

            return $this->attributes[$name];
        }

        return $default;
    }

    public static function createFromGlobals()
    {

        $method = isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : 'GET';

        $headers = \PHPLegends\Http\get_all_headers();

        $body = new Stream('php://input', 'r+');

        $protocol = isset($_SERVER['SERVER_PROTOCOL']) ? str_replace('HTTP/', '', $_SERVER['SERVER_PROTOCOL']) : '1.1';

        $uri = static::createUriFromGlobals();

        $serverRequest = new self($method, $uri, $headers, $body, $protocol, $_SERVER);


        return $serverRequest
                    ->withQueryParams($_GET)
                    ->withParsedBody($_POST)
                    ->withCookieParams($_COOKIE)
                    ->withUploadedFiles(static::normalizeUploadedFiles($_FILES));
    }

    public static function createUriFromGlobals()
    {

        $uri = new Uri();

        if (isset($_SERVER['HTTPS'])) {

            $uri = $uri->withScheme($_SERVER['HTTPS'] == 'on' ? 'https' : 'http');
        }

        if (isset($_SERVER['HTTP_HOST'])) {

            $uri = $uri->withHost($_SERVER['HTTP_HOST']);

        } elseif (isset($_SERVER['SERVER_NAME'])) {

            $uri = $uri->withHost($_SERVER['SERVER_NAME']);
        }

        if (isset($_SERVER['SERVER_PORT'])) {

            $uri = $uri->withPort($_SERVER['SERVER_PORT']);
        }

        if (isset($_SERVER['REQUEST_URI'])) {

            $uri = $uri->withPath(strtok($_SERVER['REQUEST_URI'], '?'));
        }

        if (isset($_SERVER['QUERY_STRING'])) {

            $uri = $uri->withQuery($_SERVER['QUERY_STRING']);
        }
        
        return $uri;
    }

    public function isSecure()
    {
        return $this->getUri()->getScheme() === 'https';
    }

    public function withAttributes(array $attributes)
    {
        $clone = clone $this;

        $clone->attributes = $attribute += $clone->attributes;

        return $clone;
    }

}