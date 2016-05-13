<?php

namespace PHPLegends\Http;

use Psr\Http\Message\UriInterface;

class Uri implements UriInterface
{

    protected $components = [
        'host'     => '',
        'port'     => NULL,
        'query'    => '',
        'fragment' => '',
        'scheme'   => '',
    ];

	public function __construct($uri = '')
	{
        $this->components = parse_url($uri) + $this->components;
	}

	public function getQuery()
	{
		return $this->components['query'];
	}

    public function getPort()
    {
        return $this->components['port'];
    }

    public function getAuthority()
    {

    }

    public function getHost()
    {
        return $this->components['host'];
    }

    public function getPath()
    {
        return $this->components['path'];
    }

    public function getFragment()
    {
        return $this->components['fragment'];
    }

    public function withScheme($scheme)
    {
        if (! is_string($scheme))
        {
            throw new \InvalidArgumentException('Invalid scheme');
        }

        return $this->withAttribute('scheme', $scheme);
    }

    public function getUserInfo()
    {
        $userinfo = null;

        if ($this->components['user'])
        {
            $userinfo = $this->components['user'];
        }

        if ($this->components['pass'])
        {
            $userinfo .= ':' . $this->components['pass'];
        }

        return $user;
    }

    public function withPort($port)
    {
        if (is_int($port) || is_null($port))
        {
            return $this->withAttribute('port', $port);
        }

        throw new \InvalidArgumentException('Invalid port');
    }

    public function withHost($host)
    {        
        if (! is_string($host))
        {
            throw new \InvalidArgumentException('Invalid host');
        }

        return $this->withAttribute('host', $host);
    }

    public function withUserInfo($user, $password = null)
    {
        $clone = clone $this;

        $clone->components = ['user' => $user, 'pass' => $password] + $this->components;

        return $clone;
    }

    public function withQuery($query)
    {
        if (is_array($query))
        {
            $query = http_build_query($query);

        } elseif (! is_string($query)) {

            throw new \InvalidArgumentException('Invalid query, only query or string');
        }

        return $this->withAttribute('query', $query);
    }

    public function withFragment($fragment)
    {

        if (! is_string($fragment))
        {
            throw new \InvalidArgumentException('Invalid fragment value');
        }

        return $this->withAttribute('fragment', $fragment);
    }

    protected function withAttribute($attr, $value)
    {
        $clone = clone $this;

        $clone->components[$attr] = $value;

        return $this;
    }

    public function withPath($path)
    {
        if ( ! is_string($path)) {

            throw new \InvalidArgumentException('Invalid path value');
        }

        return $this->withAttribute('path', $path);
    }

    public function getScheme()
    {
        return $this->components['scheme'];
    }

	public static function createFromServerGlobal()
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

    public function __toString()
    {

    }
}