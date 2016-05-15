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
        'user'     => '',
        'pass'     => '',
        'path'     => ''
    ];


    /**
    * 
    */
	public function __construct($uri = '')
	{
        $parts = parse_url($uri);

        if (! is_array($parts))
        {
            throw new \InvalidArgumentException("Unable to parse url '{$uri}'");  
        }

        $this->components = $parts + $this->components;
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
        $authority = '';

        if ($userinfo = $this->getUserInfo()) {
            $authority .= $userinfo . '@';
        }

        $authority .= $this->getHost();

        if ($port = $this->getPort())
        {
            $authority .= ':' . $port;
        }

        return $authority;
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

        $scheme = static::normalizeScheme($scheme);

        return $this->withAttribute('scheme', $scheme);
    }

    public function getUserInfo()
    {
        $userinfo = '';

        if ($this->components['user'])
        {
            $userinfo = $this->components['user'];
        }

        if ($this->components['pass'])
        {
            $userinfo .= ':' . $this->components['pass'];
        }

        return $userinfo;
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

        return $clone;
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


    public function __toString()
    {
        return $this->build();
    }

    public function build()
    {
        $uri = '';

        $c = $this->components;

        if ($c['scheme']) {
            $uri .= $c['scheme'] . '://';
        }

        if ($authority = $this->getAuthority()) {

            $uri .= $authority;
        }

        if ($c['path']) {
            $uri .= '/' . $c['path'];
        }

        if ($c['query']) {
            $uri .= '?' . $c['scheme'];
        }

        if ($c['fragment']) {
            $uri .= '#' .$c['fragment'];
        }

        return $uri;

    }

    protected static function normalizeScheme($scheme)
    {
        return strtolower(rtrim($scheme, ':/'));
    }

}