<?php


namespace PHPLegends\Http;

/**
 * Get all headers base on global variable $_SERVER
 * 
 * @return array
 * */

function get_all_headers() 
{ 
	$headers = []; 

	foreach ($_SERVER as $name => $value) { 

		if (substr($name, 0, 5) === 'HTTP_') {

			$header_name = mb_convert_case(strtr(substr($name, 5), ['_' => '-']), MB_CASE_TITLE);

			$headers[$header_name] = $value; 
		} 
	} 

	return $headers; 
}

/**
 * 
 * 
 * @param string $to
 * @param int $code
 * @param array $headers
 * */

function redirect($to, $code = 302, array $headers = [])
{
	return new RedirectResponse($to, $code, $headers);
}


/**
 * 
 * @param mixed $data
 * @param int $code
 * @param array $headers
 * @throws \InvalidArgumentException
 * @return Response | JsonResponse 
 * */

function response($data, $code = 200, array $headers = [])
{
	if (is_string($data) || is_object($data) && method_exists($data, '__toString')) {

		return new Response((string) $data, $code, $headers);
	}

	if (is_array($data) 
		|| $data instanceof \stdClass 
		|| $data instanceof \ArrayObject 
		|| $data instanceof \JsonSerializable) {
		return new JsonResponse($data, $code, $headers);
	}

	throw new \InvalidArgumentException(
		sprintf(
			'Cannot create response for type "%s"',
			is_object($data) ? get_class($data) : gettype($data)
		)
	);
}
