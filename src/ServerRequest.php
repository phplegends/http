<?php

namespace PHPLegends\Http;

use Psr\Http\Message\ServerRequestInterface;

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
     * Sets the value of serverParams.
     *
     * @param mixed $serverParams the server params
     *
     * @return self
     */
    protected function setServerParams($serverParams)
    {
        $this->serverParams = $serverParams;

        return $this;
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
     * Sets the value of cookieParams.
     *
     * @param mixed $cookieParams the cookie params
     *
     * @return self
     */
    protected function setCookieParams($cookieParams)
    {
        $this->cookieParams = $cookieParams;

        return $this;
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
     * Sets the value of queryParams.
     *
     * @param mixed $queryParams the query params
     *
     * @return self
     */
    protected function setQueryParams($queryParams)
    {
        $this->queryParams = $queryParams;

        return $this;
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
     * Sets the value of uploadedFiles.
     *
     * @param mixed $uploadedFiles the uploaded files
     *
     * @return self
     */
    public function setUploadedFiles($uploadedFiles)
    {
        $this->uploadedFiles = $uploadedFiles;

        return $this;
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

    /**
     * Sets the value of attributes.
     *
     * @param array $attributes the attributes
     *
     * @return self
     */
    public function setAttributes(array  $attributes)
    {
        $this->attributes = $attributes;

        return $this;
    }



    public function withCookieParams(array $cookies)
    {

    }

    public function withQueryParams(array $params)
    {

    }

    public function withUploadedFiles(array $attributes)
    {

    }

    public function getParsedBody()
    {

    }

    public function withAttribute($name, $attribute)
    {

    }

    public function withParsedBody($data)
    {

    }

    public function withoutAttribute($name)
    {

    }

    public function getAttribute($name, $default = null)
    {

    }

    public static function createFromGlobals()
    {

        $method = isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : 'GET';

        $headers = \PHPLegends\Http\get_all_headers();

        $body = new Stream('php://input', 'r+');

        $protocol = isset($_SERVER['SERVER_PROTOCOL']) ? str_replace('HTTP/', '', $_SERVER['SERVER_PROTOCOL']) : '1.1';

        $serverRequest = new self($method, $uri, $headers, $body, $protocol, $_SERVER);

        return $serverRequest
            ->withCookieParams($_COOKIE)
            ->withQueryParams($_GET)
            ->withParsedBody($_POST)
            ->withUploadedFiles($_FILES);
    }

}