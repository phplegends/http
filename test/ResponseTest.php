<?php

use PHPLegends\Http\Response;

class ResponseTest extends PHPUnit_Framework_TestCase
{

	public function setUp()
	{
		$this->resp = new Response('My test', 200, [
			'X-Dev'     => 'Testing',
			'X-Library' => 'PHPLegends\Http',
		]);
	}

	public function testGetBody()
	{
		

		$this->assertInstanceOf('PHPLegends\Http\Stream', $this->resp->getBody());

		$this->assertInstanceOf('Psr\Http\Message\StreamInterface', $this->resp->getBody());

		$this->assertEquals(
			'My test',
			$this->resp->getBody()->getContents()
		);

		// Testing if rewind is working

		$this->resp->getBody()->rewind();

		$this->assertEquals(
			'My test',
			$this->resp->getBody()->getContents()
		);
	}	


	public function testHeader()
	{

		$upper = $this->resp->getHeader('X-DEV');

		$lower = $this->resp->getHeader('x-dev');

		$this->assertEquals(['Testing'], $upper);

		$this->assertEquals(['Testing'], $lower);

		$resp = $this->resp->withHeader('X-FOO', [1, 2]);

		$this->assertEquals([1, 2], $resp->getHeader('x-foo'));

		$this->assertTrue($resp->hasHeader('x-foo'));

		$this->assertFalse($this->resp->hasHeader('x-foo'));

	}
}