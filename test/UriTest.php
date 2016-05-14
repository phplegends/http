<?php

use PHPLegends\Http\Uri;

class 	 UriTest extends PHPUnit_Framework_TestCase
{
	public function testUri()
	{
		$uri = new Uri('http://teste.com.br');

		$this->assertEquals(
			'https://teste.com.br',
			$uri->withScheme('HTTPS')->build()
		);

		$new_uri = $uri->withPath('login')->withScheme('ssh')->withUserinfo('wallace', 'senha');

		$this->assertEquals(
			'ssh://wallace:senha@teste.com.br/login',
			$new_uri->build()
		);
	}	
}