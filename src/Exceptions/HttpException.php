<?php

namespace PHPLegends\Http\Exceptions;
use PHPLegends\Http\Response;

class HttpException extends \RunTimeException implements HttpExceptionInterface
{
	protected $response;

	public function __construct($message = null, $statusCode = 500)
	{		
		parent::__construct($message);

		$this->response = new Response($this->getMessage(), $statusCode);
	}

	/**
	 * Get response of exception
	 * 
	 * @return PHPLegends\Http\Response
	 * */
	public function getResponse()
	{
		return $this->response;
	}
}