<?php

use PHPLegends\Http\ServerRequest;
use PHPLegends\Http\Exceptions\HttpException;
use PHPLegends\Http\Exceptions\MethodNotAllowedException;
use PHPLegends\Http\Exceptions\NotFoundException;


class ExceptionsTest extends PHPUnit_Framework_TestCase
{
	public function testHttpException()
	{
		// Testing
		$e = new HttpException('Internal Server Error');

		$this->assertEquals(
			'Internal Server Error',
			$e->getMessage()
		);

		$this->assertEquals(
			500,
			$e->getResponse()->getStatusCode()
		);
	}

	public function testHttpException403()
	{
		$e = new HttpException('Acesso não autorizado', 403);

		$this->assertEquals(403, $e->getResponse()->getStatusCode());

		$this->assertEquals('Acesso não autorizado', $e->getMessage());

		// All exceptions of PHPLegends\HTTP MUST implements this interface

		$this->assertInstanceOf('PHPLegends\Http\Exceptions\HttpExceptionInterface', $e);
	}


	public function testMethodNotAllowedException()
	{

		$e = new MethodNotAllowedException('Method "put" not allowed');

		$this->assertEquals('Method "put" not allowed', $e->getMessage());

		$request = ServerRequest::createFromGlobals()->withMethod('DELETE');

		$e = MethodNotAllowedException::createFromRequest($request);

		$this->assertEquals('Method "DELETE" not allowed',  $e->getMessage());
	}


	public function testNotFoundException()
	{
		$e = new NotFoundException('Page not found');

		$this->assertEquals('Page not found', $e->getMessage());

		$this->assertEquals('Page not found', (string) $e->getResponse()->getBody());

		$this->assertEquals(404, $e->getResponse()->getStatusCode());
	}

}