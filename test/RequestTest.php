<?php

use PHPLegends\Http\ServerRequest;
use PHPLegends\Http\Uri;

class RequestTest extends PHPUnit_Framework_TestCase
{
	public function setUp()
	{

		$this->uri = new Uri(
			'https://wallacemaxters:password@phplegends.com.br/repo/testing?order=desc#phplegends'
		);

		$this->req = new ServerRequest('GET', $this->uri, ['x-foo' => 'bar']);
	}

	public function testRequest()
	{
		$r1 = $this->req->withHeader('x-baz', ['foo', 'bar', 'baz']);

		$this->assertEquals('foo, bar, baz', $r1->getHeaderLine('X-BAZ'));

		$this->assertEquals(
			'phplegends.com.br',
			$this->req->getUri()->getHost()
		);

		$this->assertTrue($this->req->isSecure());

		$secure = $this->req->withUri($this->uri->withScheme('http'))->isSecure();

		$this->assertFalse($secure);
	}
}