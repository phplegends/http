<?php

include_once __DIR__ . '/../vendor/autoload.php';

use PHPLegends\Http\Response;

$response = new Response('Wallace', 302);

$response->withHeader('X-FOO', 1)
		 ->send();