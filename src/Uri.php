<?php

namespace PHPLegends\Http;

use Psr\Http\Message\UriInterface;

/**
 * @author Wallace de Souza Vizerra <wallacemaxters@gmail.com>
 * */

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
    * @param string $uri
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

    /**
     * @{inheritdoc}
     * */
	public function getQuery()
	{
		return $this->components['query'];
	}

    /**
     * @{inheritdoc}
     * */
    public function getPort()
    {
        return $this->components['port'];
    }

    /**
     * @{inheritdoc}
     * */
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

    /**
     * @{inheritdoc}
     * */
    public function getHost()
    {
        return $this->components['host'];
    }

    /**
     * @{inheritdoc}
     * */
    public function getPath()
    {
        return $this->components['path'];
    }

    /**
     * @{inheritdoc}
     * */
    public function getFragment()
    {
        return $this->components['fragment'];
    }

    /**
     * @{inheritdoc}
     * */
    public function withScheme($scheme)
    {
    
        if (! is_string($scheme))
        {
            throw new \InvalidArgumentException('Invalid scheme');
        }

        $scheme = static::normalizeScheme($scheme);

        return $this->withAttribute('scheme', $scheme);
    }


    /**
     * @{inheritdoc}
     * */
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

    /**
     * @{inheritdoc}
     * */
    public function withPort($port)
    {
        if (preg_match('/^\d+$/', $port) > 0 || is_null($port)) {

            return $this->withAttribute('port', $port);
        }

        throw new \InvalidArgumentException('Invalid port');
    }

    /**
     * @{inheritdoc}
     * */
    public function withHost($host)
    {        
        if (! is_string($host))
        {
            throw new \InvalidArgumentException('Invalid host');
        }

        return $this->withAttribute('host', $host);
    }

    /**
     * @{inheritdoc}
     * */
    public function withUserInfo($user, $password = null)
    {
        $clone = clone $this;

        $clone->components = ['user' => $user, 'pass' => $password] + $this->components;

        return $clone;
    }

    /**
     * @{inheritdoc}
     * */
    public function withQuery($query)
    {
        if (! is_string($query)) {

            throw new \InvalidArgumentException('Invalid query, only query or string');
        }

        return $this->withAttribute('query', $query);
    }

    /**
     * 
     * 
     * @param array $queryParams
     * @return self
     * */
    public function withQueryParams(array $queryParams)
    {
        $query = http_build_query($queryParams);

        return $this->withQuery($query);
    }

    /**
     * @{inheritdoc}
     * */
    public function withFragment($fragment)
    {

        if (! is_string($fragment))
        {
            throw new \InvalidArgumentException('Invalid fragment value');
        }

        return $this->withAttribute('fragment', $fragment);
    }

    /**
     * Creates a new attribute in uri, returning a cloned uri
     * 
     * @param string $attr
     * @param mixed $value
     * @return self (clone)
     * */
    protected function withAttribute($attr, $value)
    {
        $clone = clone $this;

        $clone->components[$attr] = $value;

        return $clone;
    }

    /**
     * @{inheritdoc}
     * */
    public function withPath($path)
    {
        if ( ! is_string($path)) {

            throw new \InvalidArgumentException('Invalid path value');
        }

        return $this->withAttribute('path', $path);
    }

    /**
     * @{inheritdoc}
     * */
    public function getScheme()
    {
        return $this->components['scheme'];
    }


    /**
     * @return string
     * */
    public function __toString()
    {
        return $this->build();
    }

    /**
     * @return string
     * */
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

    /**
     * Normalize scheme 
     * 
     * @param string $scheme
     * @return string
     * */
    protected static function normalizeScheme($scheme)
    {
        return strtolower(rtrim($scheme, ':/'));
    }

}