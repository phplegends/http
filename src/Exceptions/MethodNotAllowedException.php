<?php

namespace PHPLegends\Http\Exceptions;

use PHPLegends\Http\Request;

class MethodNotAllowedException extends HttpException 
{
	public function __construct($message = 'Method not allowed')
	{
		parent::__construct($message, 405);
	}

	public static function createFromRequest(Request $request)
	{
		return new self(sprintf('Method "%s" not allowed', $request->getMethod()));
	}
}